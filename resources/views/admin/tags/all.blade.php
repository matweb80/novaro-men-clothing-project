@component('admin.layouts.content',['title'=>'لیست برچسب ها'])

@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item active">لیست برچسب ها</li>
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
                <h3 class="card-title">برچسب ها</h3>

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
                        @can('create-tag')
                        <a href="{{route('admin.tags.create')}}" class="btn btn-info">ایجاد برچسب جدید</a>
                        @endcan
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <ul class="list-group list-group-flush">
                    @foreach($tags as $tag)
                    <li class="list-group-item">
                        <div class="d-flex">
                            <span>
                                {{ $tag->name }}
                            </span>
                            @canany(['delete-tag','edit-tag'])
                            <div class="actions mr-2">
                                <form action="{{ route('admin.tags.destroy',$tag->slug) }}" id="tag-{{ $tag->id }}-delete" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <a href="#" onclick="event.preventDefault(); document.querySelector('#tag-{{ $tag->id }}-delete').submit()" class="badge badge-danger">حذف</a>
                                <a href="{{route('admin.tags.edit',$tag->slug)}}" class="badge badge-primary">ویرایش</a>
                            </div>
                            @endcan
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                {{ $tags->appends(['search'=> request('search') ])->render("pagination::bootstrap-5") }}
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

@endcomponent