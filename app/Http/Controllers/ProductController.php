<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // SEO Tools
        $this->seo()
            ->setTitle('فروشگاه ' . config('app.persian_name'))
            ->setDescription(config('app.name') . ' Shopping Website\'s Products Page');

        SEOMeta::setKeywords('Products Page ,' . 'Shop Page ,' . ' خرید ' . ', صفحه فروشگاه ' . ', محصولات , ' . config('app.name') . ' , ' . config('app.persian_name'));


        $products = Product::latest()->paginate(12);

        return view('home.products', compact('products'));
    }

    public function single(Product $product)
    {
        // SEO Tools
        $this->seo()
            ->setTitle('محصول ' . $product->title . ' | ' . config('app.persian_name'))
            ->setDescription('Product ' . $product->slug . ' | ' . config('app.name') . ' Shopping Website\'s Single Product Page');

        SEOMeta::setKeywords('Product Page ,' . 'Product ,' . 'محصول ' . $product->slug . ', خرید ' . ', صفحه محصول ' . ', محصول , ' . config('app.name') . ' , ' . config('app.persian_name'));

        return view('home.single-product', compact('product'));
    }
}
