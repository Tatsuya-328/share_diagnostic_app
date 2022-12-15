@extends('layouts.app')

@section('title', config('const.appName'))

@section('body_class','nav-md')

@section('header')
    @include('sections.header')
@endsection

@section('page')
    @yield('content')
    <div class="container">
        @if(session()->has('message'))
            @php
                $alertStyle = "alert-info";
                if (session()->has('status') && session('status') === false) {
                    $alertStyle = "alert-danger";
                }
            @endphp
            <div class="alert {{$alertStyle}} col-lg-12 mb-3">
                {{session('message')}}
            </div>
        @endif
    </div>
@stop
@section('footer')
    @include('sections.footer')
@endsection

@section('styles')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ mix('assets/auth/css/auth.css') }}">
@endsection

@section('scripts')
    @parent
@endsection
