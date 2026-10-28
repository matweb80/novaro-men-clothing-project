@extends('layouts.app')

@section('content')
@routes
<div id="checkout-app"
    data-auth='@json(Auth::check())'></div>
@endsection