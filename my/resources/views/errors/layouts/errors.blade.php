@extends('layouts.app')

@section('title', config('const.appName'))

@section('body_class','nav-md')

@section('header')
    @include('sections.header')
@endsection

@section('page')
    @yield('content')
@stop
@section('footer')
    @include('sections.footer')
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ mix('assets/partner/css/partner.css') }}">
@endsection

@section('scripts')
@endsection
