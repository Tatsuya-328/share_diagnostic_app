@extends('layouts.email_app')
@section('content')

メールアドレスの変更を受付けました。
メールアドレスを変更するには下記から手続きをしてください。
{{ $url }}
※{{ config( 'const.userRegisterExpireHour') }}時間以内に手続きが行われない場合には無効になります。

このメールは、{{ config('const.appLocalName') }}のメールアドレス変更お手続きにて入力されたメールアドレスに自動送信されています。
心当たりのない方は、このメールを破棄してください。

@endsection