@extends('auth.layouts.auth')
@section('title', "本登録完了")
@section('content')
<div class="container mt-4">
    <h2 class="h3">会員登録</h2>
    <div class="mb-3">
        <h5>会員登録が完了しました</h5>
        <p>会社を登録するには、下記リンクよりログインの上会社新規登録にお進みください。</p>
        <div class="text-center"><a href="{{ route('login') }}" class="btn btn-success">ログイン画面へ</a></div>
    </div>
</div>
@endsection