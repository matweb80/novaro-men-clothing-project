@component('admin.layouts.content',['title'=>'ایجاد دسته بندی'])

@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">همه دسته بندی ها</a></li>
<li class="breadcrumb-item active">ایجاد دسته بندی</li>
@endslot

@slot('script')
<script>
    $('#categories').select2({
        'placeholder': 'دسته بندی موردنظر را انتخاب کنید'
    })

    document.getElementById('name').addEventListener('input', function() {
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
@endslot

<div class="row">
    <div class="col-lg-12">
        @include('admin.layouts.errors')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">فرم ایجاد دسته بندی</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{route('admin.categories.store' , $category->slug )}}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">نام دسته بندی</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="نام دسته بندی را وارد کنید" value="{{ old ('name' , $category->name) }}">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">پیوند دسته بندی</label>
                        <input type="text" name="slug" class="form-control" id="slug" placeholder="پیوند دسته بندی را وارد کنید" value="{{ old ('slug' , $category->slug) }}">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">دسته بندی مادر</label>
                        <select class="form-control" name="categories[]" id="categories">
                            <option value="0">ندارد</option>
                            @foreach(\App\Models\Category::all() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">ایجاد دسته بندی</button>
                    <a href="{{route('admin.categories.index')}}" class="btn btn-default float-left">لغو</a>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
    </div>
</div>

@endcomponent