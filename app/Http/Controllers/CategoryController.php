<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function showProducts(Category $category)
    {
        // SEO Tools
        $this->seo()
            ->setTitle('دسته بندی ' . $category->name . ' | ' . config('app.persian_name'))
            ->setDescription($category->name . ' Category' . ' | ' . config('app.name') . ' Shopping Website\'s Category\'s Products Page');

        // $this->seo()
        // ->twitter()
        // ->setDescription('وبسایت قوی');

        SEOMeta::setKeywords($category->name . ' Category ' . ', دسته بندی ' . $category->name . ', خرید ' . $category->name . ', فروشگاه ' . ', ' . config('app.persian_name') . ' ,' . config('app.name'));

        return view('home.categories', compact('category'));
    }
}
