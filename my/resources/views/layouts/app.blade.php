<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <title>@yield('title') | {{ config('const.appName') }}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="format-detection" content="telephone=no">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" property="stylesheet" href="{{ mix('css/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" property="stylesheet" href="{{ mix('css/jquery-ui.css') }}">
        <link rel="stylesheet" type="text/css" property="stylesheet" href="{{ mix('css/style.css') }}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <meta name="robots" content="noindex" />
        @yield('meta')
        @yield('styles')
        @yield('head')
    </head>
    <body class="@yield('body_class')">
        @yield('header')
        {{--Page--}}
        <div class="main_container">
        @yield('page')
        hoge
        </div>
        @yield('footer')

        {{--Common Scripts--}}
        <script src="{{ mix('assets/app/js/app.js') }}"></script>

        {{--Scripts--}}
        @yield('scripts')
    </body>
</html>


