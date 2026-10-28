<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function index()
    {
        // SEO Tools
        $this->seo()
            ->setTitle('پروفایل ' . Auth::user()->name . ' | ' . config('app.persian_name'))
            ->setDescription(Auth::user()->name . '\'s ' . 'Profile Page');

        SEOMeta::setKeywords('Profile Page ,' . ' صفحه پروفایل ' . ', حساب کاربری , ' . Auth::user()->name . ', وبسایت فروشگاهی , ' . config('app.name') . ' , ' . config('app.persian_name'));

        return view('profile.app');
    }
}
