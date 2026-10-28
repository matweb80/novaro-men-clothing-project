@extends('layouts.app')

@section('content')
@routes
<div id="cart-app"
    data-user='@json([
                "id" => Auth::check() ? Auth::user()->id : null,
                "name" => Auth::check() ? Auth::user()->name : null,
                ])'
    data-auth='@json(Auth::check())'></div>
@endsection