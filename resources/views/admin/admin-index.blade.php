@component('admin.layouts.content', ['title' => 'داشبورد مدیریت'])

@slot('breadcrumb')
    <li class="breadcrumb-item active">داشبورد</li>
@endslot

@php
    /*
     * قرارداد مورد انتظار از DashboardController:
     * $stats: total_orders, sales_total, product_count, low_stock_count
     * $recentOrders: مجموعه‌ای از سفارش‌ها با id, user.name, status, price, created_at
     * $topProducts: مجموعه‌ای از محصولات با title و sales_count
     * $salesChart: آرایه‌ای از ردیف‌های { label, value } برای نمودار Morris
     */
    $stats = $stats ?? [];
    $recentOrders = $recentOrders ?? collect();
    $topProducts = $topProducts ?? collect();
    $salesChart = $salesChart ?? [];
@endphp

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format((int) data_get($stats, 'total_orders', 0)) }}</h3>
                        <p>تعداد سفارش‌ها</p>
                    </div>
                    <div class="icon"><i class="fa fa-shopping-bag"></i></div>
                    <a href="{{ route('admin.orders.index') }}" class="small-box-footer">
                        مشاهده سفارش‌ها <i class="fa fa-arrow-circle-left"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format((float) data_get($stats, 'sales_total', 0)) }}</h3>
                        <p>مجموع فروش</p>
                    </div>
                    <div class="icon"><i class="fa fa-line-chart"></i></div>
                    <a href="{{ route('admin.orders.index') }}" class="small-box-footer">
                        مشاهده سفارش‌ها <i class="fa fa-arrow-circle-left"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format((int) data_get($stats, 'total_products', 0)) }}</h3>
                        <p>تعداد محصولات</p>
                    </div>
                    <div class="icon"><i class="fa fa-cubes"></i></div>
                    <a href="{{ route('admin.products.index') }}" class="small-box-footer">
                        مشاهده محصولات <i class="fa fa-arrow-circle-left"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ number_format((int) data_get($stats, 'low_stock_products', 0)) }}</h3>
                        <p>محصولات با موجودی کم</p>
                    </div>
                    <div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
                    <a href="{{ route('admin.products.index') }}" class="small-box-footer">
                        بررسی محصولات <i class="fa fa-arrow-circle-left"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <section class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-bar-chart mr-1"></i>
                            فروش در بازه اخیر
                        </h3>
                    </div>
                    <div class="card-body">
                        @if (count($salesChart))
                            <div id="sales-chart" style="height: 280px;"></div>
                        @else
                            <p class="text-muted text-center mb-0 py-5">داده‌ای برای نمایش نمودار فروش وجود ندارد.</p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">آخرین سفارش‌ها</h3>
                        <div class="card-tools">
                            <a href="{{ url('/admin/orders?type=unpaid') }}" class="btn btn-tool">همه سفارش‌ها</a>
                        </div>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>شماره</th>
                                    <th>مشتری</th>
                                    <th>وضعیت</th>
                                    <th>مبلغ</th>
                                    <th>تاریخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ data_get($order, 'user.name', '—') }}</td>
                                        <td>{{ __($order->status) ?? '—' }}</td>
                                        <td>{{ number_format((float) ($order->price ?? 0)) }}</td>
                                        <td>
                                            {{ $order->created_at ? jdate($order->created_at)->format('Y/m/d') : '—' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            سفارشی برای نمایش وجود ندارد.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">محصولات پرفروش</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-tool">همه محصولات</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            @forelse ($topProducts as $product)
                                <li class="item">
                                    <div class="product-info ml-0">
                                        <span class="product-title">{{ $product->title }}</span>
                                        <span class="product-description">
                                            تعداد فروش:
                                            {{ number_format((int) ($product->sales_count ?? 0)) }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <li class="item text-center text-muted py-3">
                                    محصولی برای نمایش وجود ندارد.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </section>
        </div>

    </div>
</section>

@if (count($salesChart))
    @section('script')
        @parent
        <script>
            $(function () {
                var chartRows = @json($salesChart);

                if (window.Morris && chartRows.length) {
                    Morris.Line({
                        element: 'sales-chart',
                        data: chartRows,
                        xkey: 'label',
                        ykeys: ['value'],
                        labels: ['فروش'],
                        resize: true,
                        parseTime: false,
                        hideHover: 'auto'
                    });
                }
            });
        </script>
    @endsection
@endif

@endcomponent


