@component('admin.layouts.content',['title'=>'ایجاد مقدار برای ویژگی'])

@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.attributes.attribute-values.index', ['attribute' => $attribute->slug]) }}">همه مقدارهای ویژگی {{$attribute->name}} </a></li>
<li class="breadcrumb-item active">ایجاد مقدار برای ویژگی {{$attribute->name}}</li>
@endslot

@slot('script')
<script>
    $('#attributes').select2({
        'placeholder': 'مقدار ویژگی موردنظر را انتخاب کنید'
    })

    document.getElementById('value').addEventListener('input', function() {
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
                <h3 class="card-title">فرم ایجاد مقدار برای ویژگی {{$attribute->name}}</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{route('admin.attributes.attribute-values.store' , ['attribute' => $attribute->slug] )}}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">نام مقدار ویژگی</label>
                        <input type="text" name="value" class="form-control" id="value" placeholder="نام مقدار ویژگی را وارد کنید" value="{{ old ('value' , $attribute_value->value) }}">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">پیوند مقدار ویژگی</label>
                        <input type="text" name="slug" class="form-control" id="slug" placeholder="پیوند مقدار ویژگی را وارد کنید" value="{{ old ('slug') }}">
                    </div>
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">ایجاد مقدار ویژگی</button>
                    <a href="{{route('admin.attributes.attribute-values.index', ['attribute' => $attribute->slug])}}" class="btn btn-default float-left">لغو</a>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
    </div>
</div>

@endcomponent