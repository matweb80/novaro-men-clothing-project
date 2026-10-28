<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;

class AboutUsController extends Controller
{
    public function index()
    {
        // SEO Tools
        $this->seo()
            ->setTitle('درباره ما' . ' | ' . config('app.persian_name'))
            ->setDescription(config('app.name') . ' Shopping Website\'s About Us Page');

        SEOMeta::setKeywords('About Us Page ,' . 'About Us ,' . ' خرید ' . ', صفحه درباره ما ' . ', درباره ما , ' . config('app.name') . ' , ' . config('app.persian_name'));

        return view('home.about-us');
    }
}
