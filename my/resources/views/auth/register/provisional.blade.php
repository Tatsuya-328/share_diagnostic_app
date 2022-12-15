@extends('auth.layouts.auth')
@section('title', "仮登録完了")
@section('content')
<div class="container mt-4">
    <h2 class="h3">会員登録</h2>
    <div class="mb-3">
        <h5>メールアドレスによる仮登録を受け付けました</h5>
        <p>ご登録いただいた<b>{{ $user->email }}</b>宛てに登録確認用のご案内をお送りしました。メールの内容を確認して、{{ config('const.appLocalName') }}のユーザー本登録を完了してください。</p>
        <p>届かない場合は、{{ config('const.emailAdminAdress') }}からのメールを受信可能にして、再度登録してください。</p>
        <div class="text-center"><a href="{{ route('login') }}" class="btn btn-success">ログイン画面へ</a></div>
    </div>
</div>
@endsection