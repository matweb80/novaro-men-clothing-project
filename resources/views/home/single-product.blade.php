@extends('layouts.app')

@section('content')
<div id="product-page-app"
    data-slug="{{ $product->slug }}"
    data-user='@json([
                "id" => Auth::check() ? Auth::user()->id : null,
                "name" => Auth::check() ? Auth::user()->name : null,
                ])'
    data-auth='@json(Auth::check())'></div>

@endsection