@extends('errors.layouts.errors')
@section('title', "お探しのページは見つかりませんでした")
@section('content')
<div class="error-covor">
    <img src="/images/error_bg.png" alt="心理テストオンラインエラー画像" width="100%">
    <div class="errorMessage_wrap">
        <h1>404</h1>
        <p>the page not found</p>
    </div>
</div>
<div class="container text-center">
    <h2 class="h6">お探しのページは見つかりませんでした</h2>
    <div class="mb-3">
        <p>リンクに問題があるかページが移動または削除された可能性があります</p>
        <div class="mt-3">
            <a class="btn btn-primary" href="{{ route('home') }}">トップへ戻る</a>
        </div>
    </div>
</div>
@endsection