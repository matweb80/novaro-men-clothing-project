<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;

class TermsController extends Controller
{
    public function index()
    {
        // SEO Tools
        $this->seo()
            ->setTitle('قوانین' . ' | ' . config('app.persian_name'))
            ->setDescription(config('app.name') . ' Shopping Website\'s Terms Page');

        SEOMeta::setKeywords('Terms Page ,' . 'Terms ,' . ', صفحه قوانین ' . ', قوانین , ' . config('app.name') . ' , ' . config('app.persian_name'));

        return view('home.terms');
    }
}
