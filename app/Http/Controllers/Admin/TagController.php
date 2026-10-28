<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:show-tags')->only(['index']);
        $this->middleware('can:create-tag')->only(['create', 'store']);
        $this->middleware('can:edit-tag')->only(['edit', 'update']);
        $this->middleware('can:delete-tag')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::query();

        if ($keyword = request('search')) {
            $tags->where('name', 'LIKE', "%{$keyword}%")->orWhere('slug', 'LIKE', "%{$keyword}%");
        }

        $tags = $tags->latest()->paginate(15);

        return view('admin.tags.all', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Tag $tag)
    {
        return view('admin.tags.create', compact('tag'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Tag $tag)
    {
        $validData = $request->validate([
            'name' => ['required', 'min:3', 'unique:tags'],
            'slug' => ['nullable', 'string', 'min:3', 'max:255'],
        ]);

        $tag->create($validData);

        Alert::success('برچسب با موفقیت ایجاد شد');
        return redirect(route('admin.tags.index'));
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
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $validData = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('tags')->ignore($tag->id)],
            'slug' => ['nullable', 'string', 'min:3', 'max:255'],
        ]);

        $tag->update($validData);

        Alert::success('برچسب با موفقیت ویرایش شد');
        return redirect(route('admin.tags.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        Alert::success('برچسب با موفقیت حذف شد');
        return back();
    }
}
