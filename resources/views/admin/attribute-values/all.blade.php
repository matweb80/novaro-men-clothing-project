@component('admin.layouts.content',['title'=>'لیست مقادیر ویژگی'])

@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item active">لیست مقدار ویژگی {{$attribute->name}}</li>
@endslot

@slot('head')
<style>
    li.list-group-item>ul.list-group {
        margin-top: 10px;
    }
</style>
@endslot

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">مقدار ویژگی {{$attribute->name}}</h3>

                <div class="card-tools d-flex">
                    <form action="">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="جستجو" value="{{request('search')}}">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="btm-group-sm mr-1">
                        @can('create-attribute')
                        <a href="{{route('admin.attributes.attribute-values.create', ['attribute' => $attribute->slug])}}" class="btn btn-info">ایجاد مقدار ویژگی {{$attribute->name}}</a>
                        @endcan
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <ul class="list-group list-group-flush">
                    @foreach($attribute_values as $attribute_value)
                    <li class="list-group-item">
                        <div class="d-flex">
                            <span>
                                {{ $attribute_value->value }}
                            </span>
                            @canany(['delete-attribute_value','edit-attribute_value'])
                            <div class="actions mr-2">
                                <form action="{{ route('admin.attributes.attribute-values.destroy',['attribute' => $attribute->slug,'attribute_value'=>$attribute_value->slug]) }}" id="attribute_value-{{ $attribute_value->id }}-delete" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <a href="#" onclick="event.preventDefault(); document.querySelector('#attribute_value-{{ $attribute_value->id }}-delete').submit()" class="badge badge-danger">حذف</a>
                                <a href="{{route('admin.attributes.attribute-values.edit',['attribute' => $attribute->slug,'attribute_value'=>$attribute_value->slug])}}" class="badge badge-primary">ویرایش</a>
                            </div>
                            @endcan
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                {{ $attribute_values->appends(['search'=> request('search') ])->render("pagination::bootstrap-5") }}
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

@endcomponent