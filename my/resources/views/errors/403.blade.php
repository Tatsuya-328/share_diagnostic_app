@extends('errors.layouts.errors')
@section('title', "403 許可されていない操作")
@section('content')
<div class="error-covor">
    <img src="/images/error_bg.png" alt="心理テストオンラインエラー画像" width="100%">
    <div class="errorMessage_wrap">
        <h1>403</h1>
        <p>許可されていない操作</p>
    </div>
</div>
<div class="container text-center">
    <h2 class="h6">許可されていない操作になります。</h2>
    <div class="mb-3">
        <p>下記のリンクよりマイページに戻り、再度操作下さい。</p>
        <div class="mt-3">
            <a class="btn btn-primary" href="{{ route('home') }}">TOPへ戻る</a>
        </div>
    </div>
</div>
@endsection