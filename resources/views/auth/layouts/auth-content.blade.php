@extends('layouts.app')

@section('content')
<div class="auth-page">
    <a href="{{ route('home') }}" class="auth-brand latin">NOVARO</a>

    <div class="auth-card">
        {{ $slot }}
    </div>

    <p class="auth-footer-note">© نوواررو — تمام حقوق محفوظ است.</p>
</div>
@endsection

@section('style')
@include('auth.partials.auth-styles')
@endsection

@section('script')
{{ $script ?? '' }}
@endsection