<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Badge;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:show-products')->only(['index']);
        $this->middleware('can:create-product')->only(['create', 'store']);
        $this->middleware('can:edit-product')->only(['edit', 'update']);
        $this->middleware('can:delete-product')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->seo()->setTitle('همه محصولات (ادمین)');

        $products = Product::query();

        if ($keyword = request('search')) {
            $products->where('title', 'LIKE', "%{$keyword}%")->orWhere('id', 'LIKE', "%{$keyword}%");
        }

        $products = $products->latest()->paginate(20);
        return view('admin.products.all', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $badges = Badge::where('is_active', true)
            ->orderByDesc('priority')
            ->get();

        return view('admin.products.create', compact('badges'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product)
    {
        $validData = $request->validate([
            'title' => ['required', 'string', 'max:255', 'unique:products,title'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'max:255'],
            'short_desc' => ['nullable', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'lt:price'],
            'sale_start_at' => ['nullable', 'date'],
            'sale_end_at' => ['nullable', 'date', 'after:sale_start_at'],
            'image' => 'required',
            'inventory' => ['required', 'integer'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku'],
            'categories' => ['required', 'array'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'attributes' => 'array',
            'badges' => ['nullable', 'array'],
            'badges.*' => [
                'integer',
                Rule::exists('badges', 'id')
                    ->where(fn($query) => $query->where('is_active', true)),
            ],
        ]);


        $product = auth()->user()->products()->create($validData);
        $product->categories()->sync($validData['categories']);
        $product->badges()->sync($validData['badges'] ?? []);

        //tags
        if (isset($validData['tags'])) {
            $tagIds = [];
            foreach ($validData['tags'] ?? [] as $item) {
                if (is_null($item)) continue;

                $tag = Tag::firstOrCreate([
                    'name' => trim($item)
                ]);

                $tagIds[] = $tag->id;
            }

            $product->tags()->sync($tagIds);
        }

        if (isset($validData['attributes']))
            $this->attachAttributesToProduct($product, $validData);

        if (isset($validData['sale_price']))
            $product->update(['is_on_special_sale' => true]);

        Alert::success('محصول موردنظر شما با موفقیت ایجاد شد');

        return redirect(route('admin.products.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $badges = Badge::where('is_active', true)
            ->orderByDesc('priority')
            ->get();

        $product->load('badges');

        return view(
            'admin.products.edit',
            compact('product', 'badges')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validData = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('products', 'title')->ignore($product->id)],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'max:255'],
            'short_desc' => ['nullable', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'lt:price'],
            'sale_start_at' => ['nullable', 'date'],
            'sale_end_at' => ['nullable', 'date', 'after:sale_start_at'],
            'image' => 'required',
            'inventory' => ['required', 'integer'],
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($product->id)],
            'categories' => ['required', 'array'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'attributes' => 'array',
            'badges' => ['nullable', 'array'],
            'badges.*' => [
                'integer',
                Rule::exists('badges', 'id')
                    ->where(fn($query) => $query->where('is_active', true)),
            ],
        ]);

        $product->update($validData);
        $product->categories()->sync($validData['categories']);
        $product->badges()->sync($validData['badges'] ?? []);

        //tags
        $product->tags()->detach();
        if (isset($validData['tags'])) {
            $tagIds = [];
            foreach ($validData['tags'] ?? [] as $item) {
                if (is_null($item)) continue;

                $tag = Tag::firstOrCreate([
                    'name' => trim($item)
                ]);

                $tagIds[] = $tag->id;
            }

            $product->tags()->sync($tagIds);
        }

        $product->attributes()->detach();

        if (isset($validData['attributes']))
            $this->attachAttributesToProduct($product, $validData);

        if (isset($validData['sale_price']))
            $product->update(['is_on_special_sale' => true]);

        Alert::success('محصول موردنظر شما با موفقیت ویرایش شد');
        return redirect(route('admin.products.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        Alert::success('محصول موردنظر شما با موفقیت حذف شد');
        return back();
    }

    protected function attachAttributesToProduct(Product $product, array $validData): void
    {
        $attributes = collect($validData['attributes']);
        $attributes->each(function ($item) use ($product) {
            if (is_null($item['name']) || is_null($item['value'])) return;

            $attr = Attribute::firstOrCreate(
                ['name' => $item['name']]
            );

            $attr_value = $attr->values()->firstOrCreate(
                ['value' => $item['value']]
            );

            $product->attributes()->attach($attr->id, ['value_id' => $attr_value->id]);
        });
    }
}
