@extends('layouts.app')

@section('content')
<div id="not-found-app"
    data-auth='@json(Auth::check())'></div>
@endsection

@section('script')
@vite('resources/js/pages/not-found/main.js')
@endsection