<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function showProducts($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $query = $category->products()->select('id', 'title', 'slug', 'price', 'image')->with('attributeValues.attribute');

        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'newest');

        $products = $query->sort($sort)->latest()->paginate(12);

        return response()->json([
            'products' => $products,
            'category' => $category
        ]);
    }

    public function index()
    {
        $categories = Category::where('parent', 0)->with('child.child')->select('id', 'name', 'slug', 'parent')->orderBy('name')->get();

        return response()->json($categories);
    }
}
