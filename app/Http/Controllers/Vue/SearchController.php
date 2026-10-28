<?php

namespace App\Http\Controllers\Vue;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');

        if (!$keyword) {
            return [];
        }
        $products = Product::query()->where('title', 'LIKE', "%{$keyword}%")->orWhere('description', 'LIKE', "%{$keyword}%")->orWhere('slug', 'LIKE', "%{$keyword}%")->get(['id', 'slug', 'title', 'price'])->take(6);

        $categories = Category::query()->where('name', 'LIKE', "%{$keyword}%")->orWhere('slug', 'LIKE', "%{$keyword}%")->with('child.child')->get()->take(3);

        $tags = Tag::query()->where('name', 'LIKE', "%{$keyword}%")->orWhere('slug', 'LIKE', "%{$keyword}%")->get()->take(3);


        // return  response()->json([$products, $categories]);
        return [
            'products' => $products,
            'categories' => $categories,
            'tags' => $tags,
        ];
    }
}
