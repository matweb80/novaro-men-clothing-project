<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function welcome()
    {
        // SEO Tools
        $this->seo()
            ->setTitle(config('app.persian_name'))
            ->setDescription(config('app.name') . ' Shopping Website');

        SEOMeta::setKeywords('خرید ' . ', وبسایت فروشگاهی ' . ', Shopping Website ' . ', فروشگاه , ' . config('app.name') . ' , ' . config('app.persian_name'));

        auth()->loginUsingId(1);
        return view('welcome');
    }
}
