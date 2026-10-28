<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function showProducts(Tag $tag)
    {
        // SEO Tools
        $this->seo()
            ->setTitle('برچسب ' . $tag->name . ' | ' . config('app.persian_name'))
            ->setDescription($tag->name . ' Tag' . ' | ' . config('app.name') . ' Shopping Website\'s Tag\'s Products Page');

        // $this->seo()
        // ->twitter()
        // ->setDescription('وبسایت قوی');

        SEOMeta::setKeywords($tag->name . ' Tag ' . ', برچسب ' . $tag->name . ', خرید ' . $tag->name . ', فروشگاه ' . ', ' . config('app.persian_name') . ' ,' . config('app.name'));

        return view('home.tags', compact('tag'));
    }
}
