@extends('layouts.app')

@section('title', config('const.appName'))

@section('body_class','nav-md')

@section('header')
    @include('sections.header')
@endsection

@section('page')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div>
                @yield('breadcrumbs')
            </div>
        </div>
        @if(session()->has('message'))
        <div class="col-md-12">
            @php
                $alertStyle = "alert-info";
                if (session()->has('status') && session('status') === false) {
                    $alertStyle = "alert-danger";
                }
            @endphp
            <div class="col-lg-12 alert {{$alertStyle}} mb-3">
                {{session('message')}}
            </div>
        </div>
        @endif
        @yield('content')
    </div>
</div>
@stop
@section('footer')
    @include('sections.footer')
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ mix('assets/partner/css/partner.css') }}">
@endsection

@section('scripts')
@endsection
