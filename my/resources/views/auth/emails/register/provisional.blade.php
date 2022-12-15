@extends('layouts.email_app')

@section('content')
{{ $user->name }}様
{{ config('const.appLocalName') }}へのご登録ありがとうございます。

会員登録はまだ完了していません。
下記のリンクから認証手続きを行うことで登録が完了します。
{{ $url }}

※{{ $expirationHour }}時間以内に認証手続きが行われない場合には無効になります。

@endsection