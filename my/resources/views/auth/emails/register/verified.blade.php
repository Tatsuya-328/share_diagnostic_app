@extends('layouts.email_app')
@section('content')
会員登録が完了しました。

▼ご利用方法
以下のURLからメールアドレスとパスワードを入力してログインしてください。
{{ $url }}

@endsection