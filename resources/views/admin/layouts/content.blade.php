@extends('admin.master')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ $title ?? 'پنل مدیریت' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        {{ $breadcrumb ?? '' }}
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            {{ $slot }}
        </div>
    </section>
@endsection

@section('head')
    @parent
    {{ $head ?? '' }}
@endsection

@section('script')
    @parent
    {{ $script ?? '' }}
@endsection