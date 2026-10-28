<?php

namespace Modules\Discount\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Discount\Models\Discount;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = Discount::query();

        if ($keyword = request('search')) {
            $discounts->where('code', 'LIKE', "%{$keyword}%")->orWhere('percent', 'LIKE', "%{$keyword}%")->orWhereHas('users', function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })->orWhereHas('products', function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%{$keyword}%");
            })->orWhereHas('categories', function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            });
        }

        $discounts = $discounts->latest()->paginate(30);
        return view('discount::admin.all', compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('discount::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:discounts,code',
            'percent' => 'required|integer|between:1,99',
            'users' => 'nullable|array|exists:users,id',
            'products' => 'nullable|array|exists:products,id',
            'categories' => 'nullable|array|exists:categories,id',
            'expired_at' => 'required'
        ]);

        $discount = Discount::create($validated);

        if (isset($validated['users']))
            $discount->users()->attach($validated['users']);
        if (isset($validated['products']))
            $discount->products()->attach($validated['products']);
        if (isset($validated['categories']))
            $discount->categories()->attach($validated['categories']);

        return redirect(route('admin.discount.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        return view('discount::admin.edit', compact('discount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'code' => ['required', Rule::unique('discounts', 'code')->ignore($discount->id)],
            'percent' => 'required|integer|between:1,99',
            'users' => 'nullable|array|exists:users,id',
            'products' => 'nullable|array|exists:products,id',
            'categories' => 'nullable|array|exists:categories,id',
            'expired_at' => 'required'
        ]);

        $discount->update($validated);

        isset($validated['users'])
            ? $discount->users()->sync($validated['users'])
            : $discount->users()->detach();

        isset($validated['products'])
            ? $discount->products()->sync($validated['products'])
            : $discount->products()->detach();

        isset($validated['categories'])
            ? $discount->categories()->sync($validated['categories'])
            : $discount->categories()->detach();

        return redirect(route('admin.discount.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
