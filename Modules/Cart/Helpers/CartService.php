<?php

namespace Modules\Cart\Helpers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Modules\Discount\Models\Discount;

class CartService
{
    protected $cart;

    protected $name = 'my-cart';


    public function __construct()
    {
        $cart = session()->get($this->name);
        if (!is_array($cart) || !isset($cart['items'])) {
            $cart = [
                'items' => [],
                'discount' => null
            ];
        }
        $this->cart = $cart;
    }

    public function totalCount()
    {
        $cart = $this->instance($this->name)->all();
        $total = 0;
        foreach ($cart as $cartItem) {
            $total += $cartItem['quantity'];
        }
        return $total;
    }

    public function count($key)
    {
        if (! $this->has($key)) return 0;
        return $this->get($key)['quantity'];
    }

    public function flush()
    {
        $this->cart = collect([
            'items' => [],
            'discount' => null
        ]);
        session()->put($this->name, $this->cart);

        return $this;
    }

    /**
     * @param array $value
     * @param mixed $obj
     * @return $this
     */
    public function put(array $value, $obj = null)
    {
        if (! is_null($obj) && $obj instanceof Model) {
            $value = array_merge($value, [
                'id' => Str::random(10),
                'subject_id' => $obj->id,
                'subject_type' => get_class($obj),
                'discount_percent' => 0
            ]);
        } elseif (! isset($value['id'])) {
            $value = array_merge($value, [
                'id' => Str::random(10),
            ]);
        }

        $this->cart['items'] = collect($this->cart['items'])->put($value['id'], $value);
        session()->put($this->name, $this->cart);

        return $this;
    }

    public function update($key, $options)
    {
        $item = collect($this->get($key, false));

        if (is_numeric($options)) {
            $item = $item->merge([
                'quantity' => $item['quantity'] + $options
            ]);
        }

        if (is_array($options)) {
            $item = $item->merge($options);
        }

        $this->put($item->toArray());

        return $this;
    }

    public function delete($key)
    {
        if ($this->has($key)) {
            $this->cart['items'] = collect($this->cart['items'])->filter(function ($item) use ($key) {
                if ($key instanceof Model) {
                    return ($item['subject_id'] != $key->id) && ($item['subject_type'] != get_class($key));
                }

                return $key != $item['id'];
            });

            session()->put($this->name, $this->cart);

            return true;
        }
        return false;
    }

    public function has($key)
    {
        if ($key instanceof Model) {
            return ! is_null(
                collect($this->cart['items'])->where('subject_id', $key->id)->where('subject_type', get_class($key))->first()
            );
        }

        return ! is_null(
            collect($this->cart['items'])->firstWhere('id', $key)
        );
    }

    public function get($key, $withRelationship = true)
    {
        $item = $key instanceof Model
            ? collect($this->cart['items'])->where('subject_id', $key->id)->where('subject_type', get_class($key))->first()
            : collect($this->cart['items'])->firstWhere('id', $key);

        return $withRelationship ?
            $this->withRelationshipIfExist($item) :
            $item;
    }

    public function all()
    {
        $cart = $this->cart;
        $cart = collect($this->cart['items'])->map(function ($item) use ($cart) {
            $item = $this->withRelationshipIfExist($item);
            $item = $this->checkDiscountValidate($item, $cart['discount']);
            return $item;
        });
        return $cart;
    }

    public function instance(string $name)
    {
        $cart = session()->get($name);
        if (!is_array($cart) || !isset($cart['items'])) {
            $cart = [
                'items' => [],
                'discount' => null
            ];
        }
        $this->cart = $cart;
        $this->name = $name;

        return $this;
    }

    protected function withRelationshipIfExist($item)
    {
        if (isset($item['subject_id']) && isset($item['subject_type'])) {
            $class = $item['subject_type'];
            $subject = (new $class())->find($item['subject_id']);
            $item[strtolower(class_basename($class))] = $subject;
            unset($item['subject_id']);
            unset($item['subject_type']);
            $product = Product::where('id', $item['product']->id)->with('attributeValues','attributeValues.attribute','attributes')->first();
            $item['product'] = $product;
            return $item;
        }

        return $item;
    }

    public function addDiscount($discount)
    {
        $this->cart['discount'] = $discount;
        session()->put($this->name, $this->cart);
    }

    public function getDiscount()
    {
        return Discount::where('code', $this->cart['discount'])->first();
    }

    protected function checkDiscountValidate($item, $discount)
    {
        $discount = Discount::where('code', $discount)->first();
        if ($discount && $discount->expired_at > now()) {
            if (
                (! $discount->products->count() && ! $discount->categories->count()) ||
                in_array($item['product']->id, $discount->products->pluck('id')->toArray()) ||
                array_intersect($item['product']->categories->pluck('id')->toArray(), $discount->categories->pluck('id')->toArray())
            ) {
                $item['discount_percent'] = $discount->percent / 100;
            }
        }
        return $item;
    }
}
