@extends('layouts.app')


@section('content')
@routes
<div id="shipping-returns-app"
    data-auth='@json(Auth::check())'></div>
@endsection