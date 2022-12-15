@extends('layouts.email_app')
@section('content')

{{ $user->name }}様
会社削除審査が完了しましたのでご案内申し上げます。

▼審査結果
-------------------------
会社ID ： {{ $company->id }}
会社名 ： {{ $company->name }}
審査結果 ： 削除見送り
理由 ： {!! showText($rejectMessage) !!}
詳細URL： {{ $url }}
-------------------------

誠に申し訳ございませんが、上記の理由にて今回は見送りとさせていただきました。
大変恐縮ではございますが、何卒ご了承ください。

@endsection