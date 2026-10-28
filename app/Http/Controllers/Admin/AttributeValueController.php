<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class AttributeValueController extends Controller
{
    public function index(Attribute $attribute)
    {
        $this->seo()->setTitle('ویژگی محصولات (ادمین)');

        $attribute_values = $attribute->values()->getQuery();

        if ($keyword = request('search')) {
            $attribute_values->where('value', 'LIKE', "%{$keyword}%")->orWhere('id', 'LIKE', "%{$keyword}%");
        }

        $attribute_values = $attribute_values->latest()->paginate(20);
        return view('admin.attribute-values.all', compact('attribute_values', 'attribute'));
    }

    public function create(AttributeValue $attribute_value, Attribute $attribute)
    {
        return view('admin.attribute-values.create', compact('attribute', 'attribute_value'));
    }

    public function store(Request $request, Attribute $attribute)
    {
        $validData = $request->validate([
            'value' => ['required', 'string', 'max:255', 'unique:attribute_values,value'],
            'slug' => ['nullable', 'string', 'max:255'],
        ]);

        AttributeValue::create([
            'attribute_id' => $attribute->id,
            ...$validData
        ]);

        Alert::success('مقدار ویژگی موردنظر شما با موفقیت ایجاد شد');

        return redirect(route('admin.attributes.attribute-values.index', ['attribute' => $attribute->slug]));
    }

    public function edit(Attribute $attribute, AttributeValue $attribute_value)
    {
        return view('admin.attribute-values.edit', compact('attribute', 'attribute_value'));
    }

    public function update(Request $request, Attribute $attribute, AttributeValue $attribute_value)
    {
        $validData = $request->validate([
            'value' => ['required', 'string', 'max:255', Rule::unique('attribute_values')->ignore($attribute_value->id)],
            'slug' => ['nullable', 'string', 'max:255'],
        ]);

        $attribute_value->update([
            'attribute_id' => $attribute->id,
            ...$validData
        ]);

        Alert::success('مقدار ویژگی موردنظر شما با موفقیت بروزرسانی شد');

        return redirect(route('admin.attributes.attribute-values.index', ['attribute' => $attribute->slug]));
    }

    public function destroy(Attribute $attribute, AttributeValue $attribute_value)
    {
        $attribute_value->delete();
        Alert::success('مقدار ویژگی موردنظر شما با موفقیت حذف شد');

        return redirect(route('admin.attributes.attribute-values.index', ['attribute' => $attribute->slug]));
    }
}
