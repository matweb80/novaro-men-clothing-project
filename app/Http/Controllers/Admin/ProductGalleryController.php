<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProductGalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:show-product-galleries')->only(['index']);
        $this->middleware('can:create-product-gallery')->only(['create', 'store']);
        $this->middleware('can:edit-product-gallery')->only(['edit', 'update']);
        $this->middleware('can:delete-product-gallery')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        $images = $product->gallery()->latest()->paginate(30);
        return view('admin.products.gallery.all', compact('product', 'images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Product $product)
    {
        return view('admin.products.gallery.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'images.*.image' => 'required',
            'images.*.alt' => 'required|min:3',
        ]);

        collect($validated['images'])->each(function ($image) use ($product) {
            $product->gallery()->create($image);
        });

        Alert::success('گالری با موفقیت ایجاد شد');
        return redirect(route('admin.products.gallery.index', ['product' => $product->slug]));
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
    public function edit(Product $product, ProductGallery $gallery)
    {
        return view('admin.products.gallery.edit', compact('product','gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, ProductGallery $gallery)
    {
         $validated = $request->validate([
            'image' => 'required',
            'alt' => 'required|min:3',
        ]);

        $gallery->update($validated);

        Alert::success('گالری با موفقیت ویرایش شد');
        return redirect(route('admin.products.gallery.index', ['product' => $product->slug]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductGallery $gallery)
    {
        $gallery->delete();
        Alert::success('گالری با موفقیت حذف شد');

        return redirect(route('admin.products.gallery.index', ['product' => $product->slug]));
    }
}
