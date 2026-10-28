@extends('layouts.app')


@section('content')
@routes
<div id="contact-us-app"
    data-auth='@json(Auth::check())'></div>
@endsection