@component('admin.layouts.content' , ['title' => 'لیست سفارشات'])
@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item active">سفارش شماره {{ $order->id }} </li>
<li class="breadcrumb-item active">لیست سفارشات</li>
@endslot

@slot('script')
<style>
    .table .admin-order-product-image {
        width: 40px;
        margin-left: 4px;
    }

    .billing {
        padding: 16px;
        border: 1px solid #ccc;
        border-radius: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.26);
        margin: 16px 0;
    }

    .billing h3,
    .table-h3 {
        text-align: center;
    }

    .billing .billing-details {
        padding: 8px;
    }

    .billing .billing-details-item {
        display: inline-block;
        margin: 0 16px 16px;
    }

    .order-table {
        margin-bottom: 16px !important;
    }

    .order-price h4 {
        text-align: left;
    }
</style>
@endslot

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">سفارش شماره {{ $order->id }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                @php
                $address= App\Models\Address::where('id',$order->address_id)->firstOrFail();
                @endphp
                <div class="billing">
                    <h3>جزئیات صورتحساب</h3>
                    <hr>
                    <div class="billing-details">
                        <div class="billing-details-item billing-full-name">
                            <h5>نام دریافت کننده</h5>
                            <span>{{$address->billing_full_name}}</span>
                        </div>
                        <div class="billing-details-item billing-mobile">
                            <h5>شماره همراه</h5>
                            <span>{{$address->billing_mobile}}</span>
                        </div>
                        <div class="billing-details-item billing-province">
                            <h5>استان</h5>
                            <span>{{$address->province_id}}</span>
                        </div>
                        <div class="billing-details-item billing-city">
                            <h5>شهر</h5>
                            <span>{{$address->city_id}}</span>
                        </div>
                        <div class="billing-details-item billing-address">
                            <h5>آدرس</h5>
                            <span>{{$address->billing_address}}</span>
                        </div>
                        <div class="billing-details-item billing-postal-code">
                            <h5>کد پستی</h5>
                            <span>{{$address->billing_postal_code}}</span>
                        </div>
                        @if(!!$address->billing_extra_description)
                        <div class="billing-details-item billing-extra-description">
                            <h5>توضیحات اضافه</h5>
                            <span>{{$address->billing_extra_description}}</span>
                        </div>
                        @endif
                    </div>
                </div>
                <table class="table table-hover">
                    <tbody>
                        <h3 class="table-h3">محصولات</h3>
                        <tr>
                            <th>آیدی محصول</th>
                            <th>محصول</th>
                            <th>تعداد محصول</th>
                            <th>قیمت محصول</th>
                            <th>قیمت کل</th>
                        </tr>

                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td><a href="/products/{{$product->slug}}"><img src="{{$product->image}}" alt="{{$product->title}} product image" loading="lazy" class="admin-order-product-image">{{$product->title}}</a></td>
                            <td>{{ $product->pivot->quantity }}</td>
                            <td>{{ number_format((float) ($product->price ?? 0)) }} تومان</td>
                            <td>{{ number_format((float) ($product->price * $product->pivot->quantity ?? 0))}} تومان</td>
                        </tr>
                        @endforeach


                    </tbody>
                </table>
                <div class="order-price">
                    <h4>
                        قیمت کل سفارش: <span>{{number_format((float) ($order->price ?? 0))}}</span> تومان
                    </h4>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                {{ $products->render("pagination::bootstrap-5") }}
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

@endcomponent