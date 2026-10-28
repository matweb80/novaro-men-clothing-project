@component('admin.layouts.content',['title'=>'ویرایش ویژگی'])

@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">همه ویژگی ها</a></li>
<li class="breadcrumb-item active">ویرایش ویژگی</li>
@endslot

@slot('script')
<script>
    $('#attributes').select2({
        'placeholder': 'ویژگی موردنظر را انتخاب کنید'
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
                <h3 class="card-title">فرم ویرایش ویژگی</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{route('admin.attributes.update' , $attribute->slug )}}" method="POST">
                @csrf
                @method('PATCH')

                <div class="card-body">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">نام ویژگی</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="نام ویژگی را وارد کنید" value="{{ old ('name' , $attribute->name) }}">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">پیوند ویژگی</label>
                        <input type="text" name="slug" class="form-control" id="slug" placeholder="پیوند ویژگی را وارد کنید" value="{{ old ('slug' , $attribute->slug) }}">
                    </div>
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">ویرایش ویژگی</button>
                    <a href="{{route('admin.attributes.index')}}" class="btn btn-default float-left">لغو</a>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
    </div>
</div>

@endcomponent