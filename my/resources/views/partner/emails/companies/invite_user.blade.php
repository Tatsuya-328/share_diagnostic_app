@extends('layouts.email_app')
@section('content')

心理テストオンライン運営です。
{{ $company->name }}から編集メンバーとして招待されました。
ログイン後、会社の編集が可能となります。

まだ会員登録がお済みでない場合には下記URLより新規登録手続きをしてください。

{{ $url }}

@endsection