@component('admin.layouts.content',['title'=>'لیست نشان ها'])

@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item active">لیست نشان ها</li>
@endslot

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">نشان ها</h3>

                <div class="card-tools d-flex">
                    <form action="">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="جستجو" value="{{request('search')}}">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="btn-group-sm mr-1">
                        @can('create-badge')
                        <a href="{{route('admin.badges.create')}}" class="btn btn-info">ایجاد نشان جدید</a>
                        @endcan
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>آیدی نشان</th>
                            <th>نام نشان</th>
                            <th>رنگ</th>
                            <th>اولویت</th>
                            <th>فعال</th>
                            <th>تعداد محصولات</th>
                            <th>اقدامات</th>
                        </tr>
                        @foreach($badges as $badge)
                        <tr>
                            <td>{{$badge->id}}</td>
                            <td>{{$badge->name}}</td>
                            <td>{{$badge->color}}</td>
                            <td>{{$badge->priority}}</td>
                            @if(!!$badge->is_active)
                            <td><span class="badge badge-success text-white">✓</span></td>
                            @else
                            <td><span class="badge badge-danger text-white">✕</span></td>
                            @endif
                            <td>{{$badge->products->count()}}</td>
                            <td class="d-flex">
                                @can('delete-badge')
                                <form action="{{ route('admin.badges.destroy', $badge->slug) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('حذف شود؟')" class="btn btn-sm btn-danger ml-1">حذف</button>
                                </form>
                                @endcan
                                @can('edit-badge')
                                <a href="{{ route('admin.badges.edit', $badge->slug) }}" class="btn btn-sm btn-primary ml-1">ویرایش</a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                {{ $badges->render("pagination::bootstrap-5") }}
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

@endcomponent