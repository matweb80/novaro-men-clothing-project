<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        //get all colors
        $colorSettings = Setting::whereIn('key', [
            'primary_color',
            'secondary_color',
            'accent_color',
            'danger_color',
            'shadow_color'
        ])->get()->pluck('value', 'key');
        return view('admin.settings.index', compact('colorSettings'));
    }

    public function update(Request $request)
    {
        //dynamic validate for colors
        $rules = [];
        foreach ($request->all() as $key => $value) {
            if (str_contains($key, '_color')) {
                $rules[$key] = 'nullable|string|max:20';
            }
        }

        $request->validate($rules);

        // create or update colors
        foreach ($request->all() as $key => $value) {
            if (str_contains($key, '_color')) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        return redirect()->back()->with('success', 'رنگ سایت با موفقیت به‌روزرسانی شد 🌈');
    }
}
