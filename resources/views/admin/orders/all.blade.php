@component('admin.layouts.content' , ['title' => 'لیست سفارشات'])
@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item active">لیست سفارشات</li>
@endslot

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">سفارشات</h3>

                <div class="card-tools d-flex">
                    <form action="">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="hidden" name="type" value="{{ request('type') }}">
                            <input type="text" name="search" class="form-control float-right" placeholder="جستجو" value="{{ request('search') }}">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>آیدی سفارش</th>
                            <th>نام کاربر</th>
                            <th>هزینه سفارش</th>
                            <th>وضعیت سفارش</th>
                            <th>شماره پیگیری پستی</th>
                            <th>زمان ثبت سفارش</th>
                            <th>اقدامات</th>
                        </tr>

                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ number_format((float) ($order->price ?? 0)) }} تومان</td>
                            @if($order->status== "unpaid")
                            <td><span class="badge badge-secondary">پرداخت نشده</span></td>
                            @elseif($order->status== "paid")
                            <td><span class="badge badge-success">پرداخت شده</span></td>
                            @elseif($order->status== "canceled")
                            <td><span class="badge badge-danger">لغو شده</span></td>
                            @elseif($order->status== "preparation")
                            <td><span class="badge badge-info">آماده سازی</span></td>
                            @elseif($order->status== "posted")
                            <td><span class="badge badge-primary">ارسال شده</span></td>
                            @elseif($order->status== "received")
                            <td><span class="badge badge-warning">دریافت شده</span></td>
                            @endif
                            <td>{{ $order->tracking_serial ?? 'هنوز ثبت نشده' }}</td>
                            <td>{{ jdate($order->created_at)->ago() }}</td>
                            <td class="d-flex">
                                @can('show-order-details')
                                <a href="{{ route('admin.orders.show' , $order->id) }}" class="btn btn-sm btn-warning  ml-1">مشاهده جزئیات سفارش</a>
                                @endcan
                                @can('show-order-payments')
                                <a href="{{ route('admin.orders.payments' , $order->id) }}" class="btn btn-sm btn-info  ml-1">مشاهده پرداخت‌ها</a>
                                @endcan
                                @can('edit-order')
                                <a href="{{ route('admin.orders.edit' , $order->id) }}" class="btn btn-sm btn-primary  ml-1">ویرایش سفارش</a>
                                @endcan
                                @can('delete-order')
                                <form action="{{ route('admin.orders.destroy' , $order->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                {{ $orders->appends([ 'search' => request('search') ])->render("pagination::bootstrap-5") }}
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

@endcomponent