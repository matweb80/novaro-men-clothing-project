<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;

class CheckoutController extends Controller
{
    public function index()
    {
        // SEO Tools
        $this->seo()
            ->setTitle('تسویه حساب' . ' | ' . config('app.persian_name'))
            ->setDescription(config('app.name') . ' Shopping Website\'s Checkout Page');

        SEOMeta::setKeywords('Checkout Page ,' . 'Payment Page ,' . ' خرید ' . ', صفحه تسویه حساب ' . ', پرداخت , ' . config('app.name') . ' , ' . config('app.persian_name'));

        return view('home.checkout');
    }
}
