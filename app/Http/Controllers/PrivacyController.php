<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;

class PrivacyController extends Controller
{
    public function index()
    {
        // SEO Tools
        $this->seo()
            ->setTitle('حریم خصوصی' . ' | ' . config('app.persian_name'))
            ->setDescription(config('app.name') . ' Shopping Website\'s Privacy Policy Page');

        SEOMeta::setKeywords('Privacy Policy Page ,' . 'Privacy Policy ,' . ' خصوصی ' . ', صفحه حریم خصوصی ' . ', حریم خصوصی , ' . config('app.name') . ' , ' . config('app.persian_name'));

        return view('home.privacy-policy');
    }
}
