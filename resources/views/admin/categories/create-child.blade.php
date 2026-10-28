@component('admin.layouts.content',['title'=>'ثبت زیر دسته'])

@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">لیست دسته ها</a></li>
<li class="breadcrumb-item active">ثبت زیر دسته "{{$category->name}}"</li>
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
                <h3 class="card-title">ثبت زیر دسته "{{$category->name}}"</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{route('admin.categories.store.child', $category->slug)}}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">نام دسته بندی</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="نام دسته بندی را وارد کنید">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">پیوند دسته بندی</label>
                        <input type="text" name="slug" class="form-control" id="slug" placeholder="پیوند دسته بندی را وارد کنید" value="{{ old ('slug') }}">
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">ثبت زیر دسته</button>
                    <a href="{{route('admin.categories.index')}}" class="btn btn-default float-left">لغو</a>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
    </div>
</div>

@endcomponent