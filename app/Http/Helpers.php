<?php

use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

if (! function_exists('isActive')) {
    function isActive($key, $activeClassName = 'active')
    {
        if (is_array($key)) {
            return in_array(Route::currentRouteName(), $key) ? $activeClassName : '';
        }
        return Route::currentRouteName() == $key ? $activeClassName : '';
    }
}

if (! function_exists('isUrl')) {
    function isUrl($url, $activeClassName = 'active')
    {
        return \request()->fullUrlIs($url) ? $activeClassName : '';
    }
}

if (! function_exists('renderCategoryOptions')) {
    function renderCategoryOptions($categories, $selectedId = null, $level = 0)
    {
        $html = '';
        $oldValue = old('categories', $selectedId);

        foreach ($categories as $category) {
            if (is_array($oldValue)) {
                $isSelected = in_array($category->id, $oldValue) ? 'selected' : '';
            } else
                $isSelected = $oldValue === $category->id ? 'selected' : '';
            $prefix = str_repeat('- ', $level);
            $html .= "<option  value='{$category->id}' {$isSelected}>{$prefix}{$category->name}</option>";

            if ($category->child->isNotEmpty()) {
                $html .= renderCategoryOptions($category->child, $oldValue, $level + 1);
            }
        }

        return $html;
    }
}

if (! function_exists('renderTagOptions')) {
    function renderTagOptions($tags, $selectedId = null)
    {
        $html = '';
        $oldValue = old('tags', $selectedId);

        foreach ($tags as $tag) {
            if (is_array($oldValue)) {
                $isSelected = in_array($tag->id, $oldValue) ? 'selected' : '';
            } else
                $isSelected = $oldValue === $tag->id ? 'selected' : '';
            $html .= "<option  value='{$tag->name}' {$isSelected}>{$tag->name}</option>";
        }

        return $html;
    }
}

if (! function_exists('parentName')) {
    function parentName($categories)
    {
        $html = '';
        $cateId = null;

        dd($categories);

        foreach ($categories as $cate) {
            dd($cate->name);
        }


        if ($cate->parent && $cate->parent !== $cate->id) {
            $cateId = $cate->parent;
            $cateName = $cate->parent;
        } else if ($cate->parent && $cate->parent === $cate->id) {
            return;
        } else {
            $cateId = $cate->id;
            $cateName = $cate->name;
        }


        $html .= `<a href="{{ route('categories-show-products', $cateId) }}" class="text-secondary text-decoration-none">{ $cateName } </a> /`;

        return new HtmlString($html);
    }
}
