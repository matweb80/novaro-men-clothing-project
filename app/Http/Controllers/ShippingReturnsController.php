<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;

class ShippingReturnsController extends Controller
{
    public function index()
    {
        // SEO Tools
        $this->seo()
            ->setTitle('سیاست مرجوعی کالا' . ' | ' . config('app.persian_name'))
            ->setDescription(config('app.name') . ' Shopping Website\'s Shipping Returns Page');

        SEOMeta::setKeywords('Shipping Returns Page ,' . 'Shipping Returns ,' . ' مرجوعی کالا ' . ', صفحه سیاست مرجوعی کالا ' . ', سیاست مرجوعی کالا , ' . config('app.name') . ' , ' . config('app.persian_name'));

        return view('home.shipping-returns');
    }
}
