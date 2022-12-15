@extends('layouts.email_app')
@section('content')

{{ $user->name }}様
会社更新審査が完了しましたのでご案内申し上げます。

▼審査結果
-------------------------
会社ID ： {{ $company->id }}
会社名 ： {{ $company->name }}
審査結果 ： 更新完了
詳細URL ： {{ $url }}
-------------------------

データのアップデートありがとうございました。
引き続きよろしくお願いいたします。

@endsection