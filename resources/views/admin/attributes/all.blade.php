@component('admin.layouts.content',['title'=>'لیست ویژگی ها'])

@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item active">لیست ویژگی ها</li>
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
                <h3 class="card-title">ویژگی ها</h3>

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
                        <a href="{{route('admin.attributes.create')}}" class="btn btn-info">ایجاد ویژگی جدید</a>
                        @endcan
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <ul class="list-group list-group-flush">
                    @foreach($attributes as $attribute)
                    <li class="list-group-item">
                        <div class="d-flex">
                            <span>
                                {{ $attribute->name }}
                            </span>
                            @canany(['delete-attribute','edit-attribute'])
                            <div class="actions mr-2">
                                <form action="{{ route('admin.attributes.destroy',$attribute->slug) }}" id="attribute-{{ $attribute->id }}-delete" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <a href="#" onclick="event.preventDefault(); document.querySelector('#attribute-{{ $attribute->id }}-delete').submit()" class="badge badge-danger">حذف</a>
                                <a href="{{route('admin.attributes.edit',$attribute->slug)}}" class="badge badge-primary">ویرایش</a>
                                <a href="{{route('admin.attributes.attribute-values.index',$attribute->slug)}}" class="badge badge-warning">مقادیر ویژگی {{$attribute->name}}</a>
                            </div>
                            @endcan
                        </div>
                        <div>
                            <small>
                                <span class="text-muted">مقادیر ویژگی: </span>
                                @if($attribute->values()->count())
                                @foreach($attribute->values->take(3) as $val)
                                ({{ $val->value }})
                                @endforeach
                                @else
                                <span class="text-danger">مقادیر ویژگی وجود ندارد</span>
                                @endif
                            </small>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                {{ $attributes->appends(['search'=> request('search') ])->render("pagination::bootstrap-5") }}
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

@endcomponent