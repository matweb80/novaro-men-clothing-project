<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only([
            'search',
            'min_price',
            'max_price',
            'attributes',
            'special_price',
            'in_stock_only',
        ]);

        $sort = $request->input('sort', 'newest');

        /*
    |--------------------------------------------------------------------------
    | Price Range
    |--------------------------------------------------------------------------
    | Always calculate the maximum from the complete products table,
    | not from the filtered query.
    |
    | This ensures the slider keeps the full available price range
    | even when the user has already applied a price filter.
    */
        $maxPrice = Product::max('price');

        $products = Product::query()
            ->with([
                'attributeValues.attribute',
                'badges',
            ])
            ->filter($filters)
            ->sort($sort)
            ->paginate(12)
            ->withQueryString();

        return response()->json([
            ...$products->toArray(),

            'price_range' => [
                'min' => 0,
                'max' => (int) ($maxPrice ?? 0),
            ],
        ]);
    }

    public function specialProducts()
    {
        $specialProducts = Product::whereNotNull('sale_price')
            ->where(function ($q) {
                $now = now();
                $q->whereNull('sale_start_at')->orWhere('sale_start_at', '<=', $now);
            })
            ->where(function ($q) {
                $now = now();
                $q->whereNull('sale_end_at')->orWhere('sale_end_at', '>=', $now);
            })->latest()->paginate(12);


        return response()->json($specialProducts);
    }

    public function show($slug)
    {
        $product = Cache::remember("product_single_page_{$slug}", now()->addMinutes(1), function () use ($slug) {
            return Product::where('slug', $slug)
                ->with([
                    'attributeValues',
                    'attributeValues.attribute',
                    'gallery',
                    'approvedComments.user',
                    'approvedComments.child.user',
                    'categories',
                    'categories.child',
                    'badges',
                ])
                ->firstOrFail();
        });

        // similar products
        $similarProducts = Cache::remember("similar_products_{$product->id}", now()->addMinutes(1), function () use ($product) {
            $similar = collect();

            $brandValue = $product->attributeValues()->whereHas('attribute', fn($q) =>
            $q->where('name', 'برند'))->first();
            if ($brandValue) {
                $attributeSimilar = Product::whereHas('attributeValues', function ($query) use ($brandValue) {
                    $query->where('value_id', $brandValue->id);
                })
                    ->where('id', '!=', $product->id)
                    ->with([
                        'attributeValues.attribute',
                        'badges',
                    ])
                    ->latest()
                    ->take(6)
                    ->get();
                $similar = $similar->merge($attributeSimilar);
            }
            if ($product->categories && $product->categories->count()) {
                $leafCategories = $product->categories->filter(fn($category) => $category->child->isEmpty());
                $categorySimilar = Product::whereHas('categories', function ($query) use ($leafCategories) {
                    $query->whereIn('categories.id', $leafCategories->pluck('id'));
                })
                    ->where('id', '!=', $product->id)
                    ->with([
                        'attributeValues.attribute',
                        'badges',
                    ])
                    ->latest()
                    ->take(6)
                    ->get();

                $similar = $similar->merge($categorySimilar);
            }
            if ($similar->count() < 6 && $product->tags->count()) {
                $tagSimilar =  Product::whereHas('tags', function ($query) use ($product) {
                    $query->whereIn('tags.id', $product->tags->pluck('id'));
                })
                    ->where('id', '!=', $product->id)
                    ->with([
                        'attributeValues.attribute',
                        'badges',
                    ])
                    ->latest()
                    ->take(12 - $similar->count())
                    ->get();
                $similar = $similar->merge($tagSimilar);
            }

            return $similar->unique('id')->take(12);
        });

        $alsoBoughtProducts = Cache::remember(
            "also_bought_{$product->id}",
            now()->addMinutes(1),
            function () use ($product) {
                $ids = DB::table('order_product as a')
                    ->select('b.product_id')
                    ->join('order_product as b', 'a.order_id', '=', 'b.order_id')
                    ->where('a.product_id', $product->id)
                    ->where('b.product_id', '!=', $product->id)
                    ->groupBy('b.product_id')
                    ->orderByRaw('COUNT(*) DESC')
                    ->limit(8)
                    ->pluck('b.product_id');

                return Product::whereIn('id', $ids)->with([
                    'attributeValues.attribute',
                    'badges',
                ])->get();
            }
        );


        $breadcrumbs = Cache::remember("product_breadcrumb_{$slug}", now()->addHours(3), function () use ($slug) {
            $product = Product::with('categories.parent')->where('slug', $slug)->firstOrFail();

            $breadcrumbsLinks = [];
            $categories = $product->categories->firstOrFail()->toArray();

            while ($categories) {
                $breadcrumbsLinks[] = [
                    'label' => $categories['name'],
                    'url' => url('/categories/' . $categories['slug'])
                ];
                $categories = $categories['parent'];
            }

            $breadcrumbsLinks = array_reverse($breadcrumbsLinks);
            $breadcrumbsLinks[] = [
                'label' => $product->title,
                'url' => url('/products/' . $product->slug)
            ];

            return $breadcrumbsLinks;
        });

        $view = $product->view_count;
        $product->update([
            'view_count' =>  $view + 1
        ]);

        return response()->json([
            'product' => $product,
            'similarProducts' => $similarProducts,
            'alsoBoughtProducts' => $alsoBoughtProducts,
            'final_price' => $product->finalPrice(),
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}
