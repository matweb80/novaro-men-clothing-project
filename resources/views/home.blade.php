@extends('layouts.app')

@section('style')
<style>
    .container {
        margin-top: 48px;
    }

    .row {
        justify-content: center;
    }

    .card {
        border: 2px var(--primary-color) solid !important;
        margin: 24px 0 !important;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.43), 0 1px 3px rgba(0, 0, 0, 0.6) !important;
    }

    .card-header {
        background-color: #E3F2FD !important;
        border-bottom: none !important;
        box-shadow: 0 0 1px #00000020, 0 1px 3px #0003;
    }

    .card-body {
        padding: 24px !important;
        background-color: #F8F8F8 !important;
    }

    a {
        text-decoration: none !important;
    }

    @media (min-width: 768px) and (max-width: 992px) {
        .container {
            max-width: 920px !important;
        }
    }

    @media (min-width: 992px) and (max-width: 1200px) {
        .container {
            max-width: 1040px !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <a href="/">بزن رو این بریم داخل سایت</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
