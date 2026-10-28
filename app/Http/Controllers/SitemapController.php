<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $categories = Category::select('slug', 'updated_at')->get();
        $tags = Tag::select('slug', 'updated_at')->get();
        return response()->view('sitemap', compact('categories', 'tags'))
            ->header('Content-Type', 'text/xml');
    }
}
