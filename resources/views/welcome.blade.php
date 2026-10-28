@extends('layouts.app')
@section('content')
@routes
<div id="app"
    data-auth='@json(Auth::check())'>
</div>

@if (Route::has('login'))
<div class="h-14.5 hidden lg:block"></div>
@endif
@endsection