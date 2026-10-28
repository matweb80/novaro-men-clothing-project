@extends('layouts.app')


@section('content')
@routes
<div id="terms-app"
    data-auth='@json(Auth::check())'></div>
@endsection