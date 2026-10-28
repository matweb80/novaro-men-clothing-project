@extends('layouts.app')


@section('content')
@routes
<div id="tag-products-app"
    data-slug="{{ $tag->slug }}"
    data-auth='@json(Auth::check())'>
</div>
@endsection