<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class AttributeController extends Controller
{
    public function getValues(Request $request)
    {
        $data = $request->validate([
            'name' => 'required'
        ]);

        $attr = Attribute::where('name', $data['name'])->first();
        if (is_null($attr))
            return response(['data' => []]);

        return response(['data' => $attr->values->pluck('value')]);
    }

    public function index()
    {
        $this->seo()->setTitle('ویژگی محصولات (ادمین)');

        $attributes = Attribute::query();

        if ($keyword = request('search')) {
            $attributes->where('title', 'LIKE', "%{$keyword}%")->orWhere('id', 'LIKE', "%{$keyword}%");
        }

        $attributes = $attributes->latest()->paginate(20);
        return view('admin.attributes.all', compact('attributes'));
    }

    public function create(Attribute $attribute)
    {
        return view('admin.attributes.create', compact('attribute'));
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:attributes,name'],
            'slug' => ['nullable', 'string', 'max:255'],
        ]);

        Attribute::create($validData);

        Alert::success('ویژگی موردنظر شما با موفقیت ایجاد شد');

        return redirect(route('admin.attributes.index'));
    }

    public function edit(Attribute $attribute)
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        $validData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('attributes')->ignore($attribute->id)],
            'slug' => ['nullable', 'string', 'max:255'],
        ]);

        $attribute->update($validData);

        Alert::success('ویژگی موردنظر شما با موفقیت بروزرسانی شد');

        return redirect(route('admin.attributes.index'));
    }

    public function destroy(Attribute $attribute)
    {
        if ($attribute->values()->count()) {
            Alert::success('ابتدا مقادیر ویژگی را حذف کنید');
            return back();
        }
        $attribute->delete();
        Alert::success('ویژگی موردنظر شما با موفقیت حذف شد');
        return back();
    }
}
