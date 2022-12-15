@extends('layouts.email_app')
@section('content')

{{ $user->name }}様
会社削除審査が完了しましたのでご案内申し上げます。

▼審査結果
-------------------------
会社ID ： {{ $company->id }}
会社名 ： {{ $company->name }}
審査結果 ： 登録削除
-------------------------

会社の登録を削除いたしました。
またの機会をお待ちしております。

@endsection