<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Modules\Cart\Helpers\Cart;

class CartController extends Controller
{
    public function addToCart($slug)
    {
        $cart = Cart::instance('my-cart');
        $product = Product::where('slug', $slug)->with('attributes')->firstOrFail();
        if ($cart->has($product)) {
            if ($cart->count($product) < $product->inventory) {
                $cart->update($product, 1);
            }
        } else {
            $cart->put(
                [
                    'quantity' => 1,
                ],
                $product
            );
        }

        $totalSpecialPrice = $cart->all()->sum(function ($item) {
            return ($item['product']->finalPrice() * $item['quantity']);
        });

        $totalDiscountPrice =
            $cart->all()->sum(function ($cart) {
                return
                    $cart['discount_percent'] != 0 ? $cart['product']->price *
                    $cart['quantity'] * (1 - $cart['discount_percent']) : null;
            });

        $totalPrice =
            $cart->all()->sum(function ($cart) {
                return
                    $cart['discount_percent'] == 0 ? $cart['product']->finalPrice() *
                    $cart['quantity'] : $cart['product']->finalPrice() *
                    $cart['quantity'] * (1 - $cart['discount_percent']);
            });

        $oldTotal = 0;
        foreach ($cart->all() as $item) {
            $oldTotal += ($item['product']->price * $item['quantity']);
        }

        return response()->json([
            'success' => true,
            'cart' => $cart->all(),
            'totalCount' => $cart->totalCount(),
            'totalSpecialPrice' => $totalSpecialPrice,
            'totalDiscountPrice' => $totalDiscountPrice,
            'totalPrice' => $totalPrice,
            'oldTotalPrice' => $oldTotal,
            'is_on_sale' => $product->isOnSale(),
            'count' => $cart->count($product),
            'product' => $product,
            'discount' => $cart->getDiscount()
        ]);
    }

    public function deleteFromCart($id)
    {
        $cart = Cart::instance('my-cart');

        $cart->delete($id);

        $totalSpecialPrice = $cart->all()->sum(function ($item) {
            return ($item['product']->finalPrice() * $item['quantity']);
        });

        $totalDiscountPrice =
            $cart->all()->sum(function ($cart) {
                return
                    $cart['discount_percent'] != 0 ? $cart['product']->price *
                    $cart['quantity'] * (1 - $cart['discount_percent']) : null;
            });

        $totalPrice =
            $cart->all()->sum(function ($cart) {
                return
                    $cart['discount_percent'] == 0 ? $cart['product']->finalPrice() *
                    $cart['quantity'] : $cart['product']->finalPrice() *
                    $cart['quantity'] * (1 - $cart['discount_percent']);
            });

        $oldTotal = 0;
        foreach ($cart->all() as $item) {
            $oldTotal += ($item['product']->price * $item['quantity']);
        }

        return response()->json([
            'status' => 'success',
            'cart' => $cart->all(),
            'totalCount' => $cart->totalCount(),
            'totalSpecialPrice' => $totalSpecialPrice,
            'totalDiscountPrice' => $totalDiscountPrice,
            'totalPrice' => $totalPrice,
            'oldTotalPrice' => $oldTotal,
        ]);
    }

    public function get(Request $request)
    {
        $instance = $request->instance ?? 'my-cart';

        $cartInstance = Cart::instance($instance);

        $totalSpecialPrice = $cartInstance->all()->sum(function ($item) {
            return ($item['product']->finalPrice() * $item['quantity']);
        });

        $totalDiscountPrice =
            $cartInstance->all()->sum(function ($cart) {
                return
                    $cart['discount_percent'] != 0 ? $cart['product']->price *
                    $cart['quantity'] * (1 - $cart['discount_percent']) : null;
            });

        $totalPrice =
            $cartInstance->all()->sum(function ($cart) {
                return
                    $cart['discount_percent'] == 0 ? $cart['product']->finalPrice() *
                    $cart['quantity'] : $cart['product']->finalPrice() *
                    $cart['quantity'] * (1 - $cart['discount_percent']);
            });

        $oldTotal = 0;
        foreach ($cartInstance->all() as $item) {
            $oldTotal += ($item['product']->price * $item['quantity']);
        }

        return response()->json([
            'cart' => $cartInstance->all(),
            'totalCount' => $cartInstance->totalCount(),
            'totalSpecialPrice' => $totalSpecialPrice,
            'totalDiscountPrice' => $totalDiscountPrice,
            'totalPrice' => $totalPrice,
            'oldTotalPrice' => $oldTotal,
            'discount' => $cartInstance->getDiscount()
        ]);
    }

    public function getProduct($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $cart = Cart::instance('my-cart');


        return response()->json([
            'cart' => $cart->all(),
            'count' => $cart->count($product),
        ]);
    }

    public function quantityChange(Request $request, $slug)
    {
        $cart = Cart::instance('my-cart');
        $product = Product::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'quantity' => 'required',
            'id' => 'required',
            'cart' => 'required',
        ]);


        if ($cart->has($data['id'])) {
            $cart->update($data['id'], [
                'quantity' => $data['quantity']
            ]);

            $totalSpecialPrice = $cart->all()->sum(function ($item) {
                return ($item['product']->finalPrice() * $item['quantity']);
            });

            $totalDiscountPrice =
                $cart->all()->sum(function ($cart) {
                    return
                        $cart['discount_percent'] != 0 ? $cart['product']->price *
                        $cart['quantity'] * (1 - $cart['discount_percent']) : null;
                });

            $totalPrice =
                $cart->all()->sum(function ($cart) {
                    return
                        $cart['discount_percent'] == 0 ? $cart['product']->finalPrice() *
                        $cart['quantity'] : $cart['product']->finalPrice() *
                        $cart['quantity'] * (1 - $cart['discount_percent']);
                });

            $oldTotal = 0;
            foreach ($cart->all() as $item) {
                $oldTotal += ($item['product']->price * $item['quantity']);
            }

            return response([
                'status' => 'success',
                'cart' => $cart->all(),
                'count' => $cart->count($product),
                'totalCount' => $cart->totalCount(),
                'totalSpecialPrice' => $totalSpecialPrice,
                'totalDiscountPrice' => $totalDiscountPrice,
                'totalPrice' => $totalPrice,
                'oldTotalPrice' => $oldTotal,
                'is_on_sale' => $product->isOnSale(),
                'threeAttributes' => $product->attributes->take(3)
            ]);
        }

        return response(['status' => 'error'], 404);
    }
}
