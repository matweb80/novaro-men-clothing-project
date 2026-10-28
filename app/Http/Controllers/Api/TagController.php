<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function showProducts($slug, Request $request)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        $query = $tag->products()->select('id', 'title', 'slug', 'price', 'image')->with('attributeValues.attribute');

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
            'tag' => $tag
        ]);
    }
}
