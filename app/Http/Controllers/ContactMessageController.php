<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;

class ContactMessageController extends Controller
{
    public function create()
    {
        // SEO Tools
        $this->seo()
            ->setTitle('تماس با ما' . ' | ' . config('app.persian_name'))
            ->setDescription(config('app.name') . ' Shopping Website\'s Contact Us Page');

        SEOMeta::setKeywords('Contact Us Page ,' . 'Contact Us ,' . ' خرید ' . ', صفحه تماس با ما ' . ', تماس با ما , ' . config('app.name') . ' , ' . config('app.persian_name'));
        return view('home.contact-us');
    }
}
