<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:show-categories')->only(['index']);
        $this->middleware('can:create-category')->only(['create', 'store']);
        $this->middleware('can:edit-category')->only(['edit', 'update']);
        $this->middleware('can:delete-category')->only(['destroy']);
        $this->middleware('can:create-category-child')->only(['createChild','storeChild']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query();

        if ($keyword = request('search')) {
            $categories->where('name', 'LIKE', "%{$keyword}%")->orWhere('slug', 'LIKE', "%{$keyword}%");
        }

        $categories = $categories->whereParent(0)->latest()->paginate(10);

        return view('admin.categories.all', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Category $category)
    {
        return view('admin.categories.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Category $category)
    {
        $validData = $request->validate([
            'name' => ['required', 'min:2', 'unique:categories'],
            'slug' => ['nullable', 'string', 'min:2', 'max:255'],
        ]);
        
        if ($request->categories === 0) {
            $category->parent = 0;
        } else {
            $category->parent = $request->categories[0];
        }

        $validData['parent'] = $category->parent;

        $category->create($validData);

        Alert::success('دسته بندی با موفقیت ایجاد شد');
        return redirect(route('admin.categories.index'));
    }

    public function createChild(Category $category)
    {
        return view('admin.categories.create-child', compact('category'));
    }

    public function storeChild(Request $request, Category $category)
    {
        $validData = $request->validate([
            'name' => ['required', 'min:2', 'unique:categories'],
            'slug' => ['nullable', 'string', 'min:2', 'max:255'],
        ]);

        $validData['parent'] = $category->id;

        $category->create($validData);

        Alert::success('زیر دسته با موفقیت ایجاد شد');
        return redirect(route('admin.categories.index'));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validData = $request->validate([
            'name' => ['required', 'min:2', Rule::unique('categories')->ignore($category->id)],
            'slug' => ['nullable', 'string', 'min:2', 'max:255'],
        ]);

        if ($request->categories === 0) {
            $category->parent = 0;
        } else {
            $category->parent = $request->categories[0];
        }

        $validData['parent'] = $category->parent;

        $category->update($validData);

        Alert::success('دسته بندی با موفقیت ویرایش شد');
        return redirect(route('admin.categories.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        Alert::success('دسته بندی با موفقیت حذف شد');
        return back();
    }
}
