@extends('layouts.email_app')
@section('content')

{{ $user->name }}様

会社登録審査が完了しましたのでご案内申し上げます。

▼審査結果
-------------------------
会社ID ： {{ $company->id }}
会社名 ： {{ $company->name }}
審査結果 ： 掲載
詳細URL ： {{ $url }}
-------------------------

会社が掲載されました。
引き続きよろしくお願いいたします。

@endsection