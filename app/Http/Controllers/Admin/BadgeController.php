<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class BadgeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:show-products')->only(['index']);
        $this->middleware('can:create-product')->only(['create', 'store']);
        $this->middleware('can:edit-product')->only(['edit', 'update']);
        $this->middleware('can:delete-product')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->seo()->setTitle('مدیریت نشان‌های محصولات');

        $badges = Badge::withCount('products')
            ->orderByDesc('priority')
            ->paginate(20);

        return view('admin.badges.all', compact('badges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.badges.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validData = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100', 'unique:badges,slug'],
            'color' => ['nullable', 'string', 'max:20'],
            'priority' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validData['is_active'] = $request->boolean('is_active');

        Badge::create($validData);

        Alert::success('نشان موردنظر با موفقیت ایجاد شد');

        return redirect()->route('admin.badges.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Badge $badge)
    {
        return view('admin.badges.edit', compact('badge'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Badge $badge)
    {
        $validData = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => [
                'required',
                'string',
                'max:100',
                Rule::unique('badges', 'slug')->ignore($badge->id),
            ],
            'color' => ['nullable', 'string', 'max:20'],
            'priority' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validData['is_active'] = $request->boolean('is_active');

        $badge->update($validData);

        Alert::success('نشان موردنظر با موفقیت ویرایش شد');

        return redirect()->route('admin.badges.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Badge $badge)
    {
        $badge->delete();

        Alert::success('نشان موردنظر با موفقیت حذف شد');

        return back();
    }
}
