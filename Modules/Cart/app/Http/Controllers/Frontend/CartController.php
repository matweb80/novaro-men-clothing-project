<?php

namespace Modules\Cart\Http\Controllers\Frontend;

use App\Http\Controllers\Controller as ControllersController;
use Modules\Cart\Helpers\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Artesaos\SEOTools\Facades\SEOMeta;

class CartController extends ControllersController
{
    public function cart()
    {
        // SEO Tools
        $this->seo()
            ->setTitle('سبد خرید' . ' | ' . config('app.persian_name'))
            ->setDescription(config('app.name') . ' Shopping Website\'s Cart Page');

        SEOMeta::setKeywords('Cart Page ,' . ' Cart ,' . ' خرید ' . ', صفحه سبد خرید ' . ', سبد خرید , ' . config('app.name') . ' , ' . config('app.persian_name'));

        return view('cart::frontend.cart');
    }
}
