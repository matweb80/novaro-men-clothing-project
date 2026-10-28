@extends('layouts.app')

@section('content')
<div id="profile-page-app"
    data-user='@json([
                "id" => Auth::check() ? Auth::user()->id : null,
                "name" => Auth::check() ? Auth::user()->name : null,
                "email" => Auth::check() ? Auth::user()->email : null
                ])'
    data-auth='@json(Auth::check())'></div>
@endsection