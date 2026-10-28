@extends('layouts.app')

@section('script')
<script>
    function changeQuantity(event, id, cartName = 'my-cart') {
        //
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })

        //
        $.ajax({
            type: 'POST',
            url: '/cart/quantity/change',
            data: JSON.stringify({
                id: id,
                quantity: event.target.value,
                cart: cartName,
                _method: 'patch'
            }),
            success: function(res) {
                location.reload();
            }
        });
    }
</script>
<style>
    .discount-form {
        width: 300px;
    }

    .discount-container {
        position: relative;
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 8px;
    }

    .discount-input {
        border: none !important;
    }

    .discount-input:focus {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }

    .discount-button {
        position: absolute;
        top: 15%;
        left: 8px;
        margin-right: 8px;
    }

    .cart-empty-container {
        text-align: center;
    }

    .cart-empty-container h3 {
        font-size: 28px;
        font-weight: 600;
    }

    .media-body img {
        width: 40px;
        margin-left: 4px;
    }

    a{
        text-decoration: none;
    }

    table tbody td.quantity-td {
        padding: 12px;
    }
</style>
@endsection

@section('content')
<div class="my-5 mx-4">
    <!-- Shopping cart table -->
    @php
    $cartCount = Cart::instance('my-cart')->all()->count();
    @endphp
    @if(!!$cartCount)
    <div class="card">
        <div class="card-header">
            <h2>سبد خرید</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered m-0">
                    <thead>
                        <tr>
                            <!-- Set columns width -->
                            <th class="text-center py-2 px-3" style="min-width: 200px;">محصول</th>
                            <th class="text-right py-2 px-3" style="width: 100px;">قیمت واحد</th>
                            <th class="text-center py-2 px-3" style="width: 80px;">تعداد</th>
                            <th class="text-right py-2 px-3" style="width: 100px;">قیمت نهایی</th>
                            <th class="text-center align-middle py-3 px-0" style="width: 40px;"><a href="#" class="shop-tooltip float-none text-light" title="" data-original-title="Clear cart"><i class="ino ion-md-trash"></i></a></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(Cart::instance('my-cart')->all() as $cart)
                        @if(isset($cart['product']))
                        @php
                        $product = $cart['product']
                        @endphp
                        <tr>
                            <td class="p-4">
                                <div class="media align-items-center">
                                    <div class="media-body">
                                        <a href="/products/{{$product->slug}}" class="d-block text-dark"><img src="{{$product->image}}" alt="cart product image" loading="lazy">{{ $product->title }} @if($product->isOnSale()) <span class="text-danger">(محصول فروش ویژه)</span> @endif</a>
                                        @if($product->attributes)
                                        <small>
                                            @foreach($product->attributes->take(3) as $attr)
                                            <span class="text-muted">{{ $attr->name }}: </span> {{ $attr->pivot->value->value }}
                                            @endforeach
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            @if(! $cart['discount_percent'] && !$product->isOnSale())
                            <td class="text-right font-weight-semibold align-middle p-4">{{ $product->finalPrice() }} تومان</td>
                            @else
                            <td class="text-right font-weight-semibold align-middle p-4">
                                <del class="text-danger text-sm">{{ $product->price }} تومان</del>
                                <span>{{ $product->finalPrice() * (1-$cart['discount_percent']) }} تومان</span>
                            </td>
                            @endif
                            <td class="align-middle qauntity-td">
                                <select onchange="changeQuantity(event,'{{ $cart['id'] }}', 'my-cart')" class="form-control text-center">
                                    @foreach(range(1 , $product->inventory) as $item)
                                    <option value="{{ $item }}" {{  $cart['quantity'] == $item ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </td>
                            @if(! $cart['discount_percent'] && !$product->isOnSale())
                            <td class="text-right font-weight-semibold align-middle p-4">تومان {{ $product->finalPrice() * $cart['quantity'] }}</td>
                            @else
                            <td class="text-right font-weight-semibold align-middle p-4">
                                <del class="text-danger text-sm">تومان {{ $product->price * $cart['quantity'] }}</del>
                                <span>{{ $product->finalPrice() * $cart['quantity'] * (1-$cart['discount_percent']) }} تومان</span>
                            </td>
                            @endif
                            <td class="text-center align-middle px-0">
                                <form action="{{route('cart.destroy', $cart['id'] )}}" id="delete-product-{{ $product->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <a href="#" onclick="event.preventDefault();document.getElementById('delete-product-{{ $product->id }}').submit()" class="shop-tooltip close float-none text-danger">×</a>
                            </td>
                        </tr>
                        @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- / Shopping cart table -->
            <div class="d-flex flex-wrap justify-content-between align-items-center pb-4">
                @if(Module::isEnable('discount'))
                @if($cart= Cart::getDiscount())
                <form action="{{ route('cart.discount.delete') }}" method="post" class="ml-auto mr-4 mt-2">
                    @csrf
                    @method('DELETE')

                    <input type="hidden" name="cart" value="my-cart">
                    <div class="bg-danger rounded-3 p-1">
                        <span>حذف کد تخفیف {{$cart->code}}</span>
                        <button type="submit" class="btn btn-danger">X</button>
                    </div>
                    <span class="mt-1">میزان تخفیف: <span class="text-success">{{ $cart->percent }} درصد</span></span>
                </form>
                @else
                <form action="{{ route('cart.discount.check') }}" method="post" class="mt-4 discount-form">
                    @csrf
                    <input type="hidden" name="cart" value="my-cart">
                    <div class="discount-container">
                        <input type="text" class="form-control discount-input" name="discount" placeholder="کد تخفیف دارید؟">
                        <button type="submit" class="btn btn-primary discount-button">اعمال تخفیف</button>
                    </div>
                    @if($errors->has('discount'))
                    <div class="text-danger text-sm mt-2">{{ $errors->first('discount') }}</div>
                    @endif
                </form>
                @endif
                @endif
                <div class="d-flex mr-auto">
                    {{-- <div class="text-right mt-4 mr-5">--}}
                    {{-- <label class="text-muted font-weight-normal m-0">Discount</label>--}}
                    {{-- <div class="text-large"><strong>$20</strong></div>--}}
                    {{-- </div>--}}
                    <div class="text-right mt-4">
                        <label class="text-muted font-weight-normal m-0">قیمت کل</label>

                        @php
                        $totalPrice = Cart::instance('my-cart')->all()->sum(function($cart) {
                        return $cart['discount_percent'] == 0
                        ? $cart['product']->finalPrice() * $cart['quantity']
                        : $cart['product']->finalPrice() * $cart['quantity'] * (1-$cart['discount_percent']);
                        });
                        @endphp
                        <div class="text-large"><strong>{{ $totalPrice }} تومان</strong></div>
                    </div>
                </div>
            </div>

            <div class="float-left">
                <a href="/checkout" class="btn btn-lg px-5 btn-primary mt-2">ادامه جهت تسویه حساب</a>
            </div>

        </div>
    </div>
    @else
    <div class="cart-empty-container">
        <img src="{{ asset('/images/empty-cart.webp') }}" alt="cart empty">
        <h3>سبد خرید خالی است☹️</h3>
        <a href="/products" class="btn btn-lg px-4 btn-primary mt-2">بریم فروشگاه <i class="fa fa-shopping-cart mr-2"></i></a>
    </div>
    @endif
</div>
@endsection