@extends('layouts.app')


@section('content')
@routes
<div id="privacy-policy-app"
    data-auth='@json(Auth::check())'></div>
@endsection