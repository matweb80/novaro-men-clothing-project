@extends('layouts.app')


@section('content')
@routes
<div id="products-app"
    data-auth='@json(Auth::check())'></div>
@endsection