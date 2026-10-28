@component('admin.layouts.content',['title'=>'ویرایش محصول'])

@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">لیست محصولات</a></li>
<li class="breadcrumb-item active">ویرایش محصول</li>
@endslot

@slot('script')
<script src="{{ asset('ckeditor-4-full/ckeditor.js') }}"></script>

<script>
    CKEDITOR.replace('description', {
        filebrowserImageBrowseUrl: '/file-manager/ckeditor'
    });
    CKEDITOR.replace('short_desc', {
        filebrowserImageBrowseUrl: '/file-manager/ckeditor'
    });

    document.addEventListener("DOMContentLoaded", function() {

        document.getElementById('button-image').addEventListener('click', (event) => {
            event.preventDefault();

            window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
        });
    });

    // set file link
    function fmSetLink($url) {
        document.getElementById('image_label').value = $url;
    }

    $('#categories').select2({
        'placeholder': 'دسته بندی موردنظر را انتخاب کنید'
    });

    $('#tags').select2({
        'placeholder': 'برچسب موردنظر را انتخاب یا اضافه کنید',
        tags: true,
        tokenSeparators: [',', '،']
    });

    $('#badges').select2({
        'placeholder': 'نشان محصول را انتخاب کنید'
    });

    let changeAttributeValues = (event, id) => {
        let valueBox = $(`select[name='attributes[${id}][value]']`);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
        });

        $.ajax({
            type: 'POST',
            url: '/admin/attribute/values',
            data: JSON.stringify({
                name: event.target.value
            }),
            success: function(res) {
                valueBox.html(`
                    <option value="" selected>انتخاب کنید</option>
                    ${
                        res.data.map(function(item){
                            return `<option value="${item}">${item}</option>`
                        })
                    }
                `);
            }
        });
    }

    let createNewAttr = ({
        attributes,
        values,
        id
    }) => {
        return `
            <div class="row" id="attribute-${id}">
                <div class="col-5">
                    <div class="form-group">
                        <label>عنوان ویژگی</label>
                        <select name="attributes[${id}][name]" onchange="changeAttributeValues(event, ${id});" class="attribute-select form-control">
                            <option value="">انتخاب کنید</option>
                            ${
                                attributes.map(function(item){
                                    return `<option value="${item}">${item}</option>`
                                })
                            }
                        </select>
                    </div>
                </div>
                <div class="col-5">
                    <div class="form-group">
                        <label>مقدار ویژگی</label>
                        <select name="attributes[${id}][value]"class="attribute-select form-control">
                            <option value="">انتخاب کنید</option>
                            ${
                                values.map(function(item){
                                    return `<option value="${item}">${item}</option>`
                                })
                            }
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <label>اقدامات</label>
                    <div>
                        <button type="button" class="btn btn-sm btn-warning" onclick="document.getElementById('attribute-${id}').remove()">حذف</button>
                    </div>
                </div>
            </div>
        `
    }

    $('#add_product_attribute').click(function() {
        let attributesSection = $('#attribute_section');
        let id = attributesSection.children().length;


        let attributes = $('#attributes').data('attributes');

        attributesSection.append(
            createNewAttr({
                attributes,
                values: [],
                id
            })
        );

        $('.attribute-select').select2({
            tags: true
        });
    })
    $('.attribute-select').select2({
        tags: true
    });

    document.getElementById('title').addEventListener('input', function() {
        let name = this.value;
        let slug = name
            .toLowerCase()
            .trim()
            .replace(/[^\u0600-\u06FFa-zA-Z0-9\s-]/g, '') // مجاز: فارسی و لاتین
            .replace(/\s+/g, '-');
        document.getElementById('slug').value = slug;
    });

    document.getElementById('slug').addEventListener('blur', function() {
        this.value = this.value
            .toLowerCase()
            .trim()
            .replace(/[^\u0600-\u06FFa-zA-Z0-9\s-]/g, '') // مجاز: فارسی و لاتین
            .replace(/\s+/g, '-');
    });
</script>
<style>
    .w-49 {
        width: 49%;
    }
</style>
@endslot

<div class="row">
    <div class="col-lg-12">
        @include('admin.layouts.errors')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">فرم ویرایش محصول</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <div id="attributes" data-attributes="{{ json_encode(\App\Models\Attribute::all()->pluck('name')) }}"></div>
            <form class="form-horizontal" action="{{route('admin.products.update', $product->slug)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <div class="card-body">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">نام محصول <span class="text-danger req">*</span></label>
                        <input type="text" name="title" class="form-control" id="title" placeholder="نام محصول را وارد کنید" value="{{ old('title' , $product->title ) }}">
                    </div>
                    <div class="form-group">
                        <label for="slug" class="col-sm-2 control-label">پیوند محصول <span class="text-danger req">*</span></label>
                        <input type="text" name="slug" class="form-control" id="slug" placeholder="پیوند محصول را وارد کنید" value="{{ old('slug' , $product->slug ) }}">
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">توضیحات محصول <span class="text-danger req">*</span></label>
                        <textarea name="description" class="form-control" id="description" placeholder="توضیحات محصول را وارد کنید" rows="5">{{ old('description' , $product->description ) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="short_desc" class="col-sm-2 control-label">توضیحات کوتاه</label>
                        <textarea name="short_desc" class="form-control" id="short_desc" placeholder="توضیحات محصول را وارد کنید" rows="5">{{ old('short_desc' , $product->short_desc ) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-2 control-label">قیمت اصلی <span class="text-danger req">*</span></label>
                        <div class="input-group">
                            <input type="number" name="price" class="form-control" id="price" placeholder="قیمت را به تومان وارد کنید" value="{{ old('price' , $product->price ?? '' ) }}">
                            <span class="input-group-text">تومان</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sale_price" class="col-sm-2 control-label">قیمت فروش ویژه</label>
                        <div class="input-group">
                            <input type="number" name="sale_price" class="form-control" id="sale_price" placeholder="قیمت را به تومان وارد کنید" value="{{ old('sale_price' , $product->sale_price ?? '' ) }}">
                            <span class="input-group-text">تومان</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="form-group w-49">
                            <label>شروع فروش ویژه</label>
                            <input type="datetime-local" name="sale_start_at"
                                value="{{ old('sale_start_at', optional($product->sale_start_at)->format('Y-m-d\TH:i')) }}" class="form-control">
                        </div>

                        <div class="form-group w-49">
                            <label>پایان فروش ویژه</label>
                            <input type="datetime-local" name="sale_end_at"
                                value="{{ old('sale_end_at', optional($product->sale_end_at)->format('Y-m-d\TH:i')) }}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inventory" class="col-sm-2 control-label">موجودی محصول <span class="text-danger req">*</span></label>
                        <input type="number" name="inventory" class="form-control" id="inventory" placeholder="موجودی را وارد کنید" value="{{ old('inventory' , $product->inventory ) }}">
                    </div>
                    <div class="form-group">
                        <label for="sku" class="col-sm-2 control-label">شناسه محصول (SKU)</label>
                        <input type="text" name="sku" class="form-control" id="sku" placeholder="شناسه محصول را وارد کنید" value="{{ old('sku' , $product->sku ) }}">
                    </div>
                    <div class="form-group">
                        <hr>
                        <label class="col-sm-2 control-label">آپلود تصویر شاخص <span class="text-danger req">*</span></label>
                        <div class="input-group mb-2">
                            <input type="text" id="image_label" class="form-control" name="image" dir="ltr" value="{{ old('image' ,$product->image ) }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="button-image">انتخاب</button>
                            </div>
                        </div>
                        <img class="w-25" src="{{ $product->image }}" alt="">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">دسته بندی ها <span class="text-danger req">*</span></label>
                        <select class="form-control" name="categories[]" id="categories" dir="rtl" multiple>
                            {!! renderCategoryOptions(\App\Models\Category::where('parent', 0 )->with('child')->get() , $product->categories->pluck('id')->toArray()) !!}
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">برچسب ها</label>
                        <select class="form-control" name="tags[]" id="tags" dir="rtl" multiple>
                            {!! renderTagOptions(\App\Models\Tag::all(), $product->tags->pluck('id')->toArray()) !!}
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">
                            نشان محصول
                        </label>

                        @php
                        $selectedBadges = old(
                        'badges',
                        $product->badges->pluck('id')->toArray()
                        );
                        @endphp

                        <select
                            class="form-control"
                            name="badges[]"
                            id="badges"
                            dir="rtl"
                            multiple>
                            @foreach($badges as $badge)
                            <option
                                value="{{ $badge->id }}"
                                {{ in_array($badge->id, $selectedBadges) ? 'selected' : '' }}>
                                {{ $badge->name }}
                            </option>
                            @endforeach
                        </select>

                        <small class="form-text text-muted">
                            «ویژه» و «جدید» به صورت خودکار مدیریت می‌شوند.
                        </small>
                    </div>
                    <h6>ویژگی محصول</h6>
                    <hr>
                    <div id="attribute_section">
                        @foreach($product->attributes as $attribute)
                        <div class="row" id="attribute-{{ $loop->index }}">
                            <div class="col-5">
                                <div class="form-group">
                                    <label>عنوان ویژگی</label>
                                    <select name="attributes[{{ $loop->index }}][name]" onchange="changeAttributeValues(event, {{ $loop->index }});" class="attribute-select form-control">
                                        <option value="">انتخاب کنید</option>
                                        @foreach(\App\Models\Attribute::all() as $attr)
                                        <option value="{{ $attr->name }}" {{$attr->name == $attribute->name ? 'selected' : ''}}>{{ $attr->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-group">
                                    <label>مقدار ویژگی</label>
                                    <select name="attributes[{{ $loop->index }}][value]" class="attribute-select form-control">
                                        <option value="">انتخاب کنید</option>
                                        @foreach($attribute->values as $value)
                                        <option value="{{ $value->value }}" {{$value->id === $attribute->pivot->value_id ? 'selected' : ''}}>{{ $value->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <label>اقدامات</label>
                                <div>
                                    <button type="button" class="btn btn-sm btn-warning" onclick="document.getElementById('attribute-{{ $loop->index }}').remove()">حذف</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button class="btn btn-sm btn-danger" type="button" id="add_product_attribute">ویژگی جدید</button>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">ویرایش محصول</button>
                    <a href="{{route('admin.products.index')}}" class="btn btn-default float-left">لغو</a>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
    </div>
</div>

@endcomponent