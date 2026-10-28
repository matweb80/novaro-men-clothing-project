<?php

namespace App\Http\Controllers\Vue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;

class TranslationController extends Controller
{
    public function index($locale)
    {
        App::setLocale($locale);

        $langPath = base_path("lang/{$locale}");

        $translations = [];

        // اگر فایل‌های PHP داشته باشیم
        foreach (File::allFiles($langPath) as $file) {
            $name = basename($file->getRealPath(), '.php');
            $translations[$name] = include $file->getRealPath();
        }

        // اگر فایل json وجود داشته باشد (مثلاً lang/fa.json)
        $jsonPath = base_path("lang/{$locale}.json");
        if (File::exists($jsonPath)) {
            $jsonTranslations = json_decode(File::get($jsonPath), true);
            $translations = array_merge($translations, $jsonTranslations);
        }

        return response()->json($translations);
    }
}
