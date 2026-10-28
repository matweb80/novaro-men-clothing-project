@extends('layouts.app')


@section('content')
@routes
<div id="about-us-app"
    data-auth='@json(Auth::check())'></div>
@endsection