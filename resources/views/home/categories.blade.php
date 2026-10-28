@extends('layouts.app')


@section('content')
@routes
<div id="category-products-app"
    data-slug="{{ $category->slug }}"
    data-auth='@json(Auth::check())'></div>
@endsection