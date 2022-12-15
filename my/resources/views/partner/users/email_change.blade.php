@extends('partner.layouts.partner')

@section('title', 'メールアドレス変更')

@section('breadcrumbs')
{!!  Breadcrumbs::render('user.email')  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">メールアドレス変更</h2>
    <div class="mb-3">
        <h5>メールアドレス変更を受け付けました</h5>
        <p>ご登録いただいた<b>{{ $user->change_email }}</b>宛てにメールアドレス変更に関するご案内をお送りしました。メールの内容を確認して、メールアドレスの認証手続きを完了させてください。</p>

        <p>届かない場合は、{{ config('const.emailAdminAdress') }}からのメールを受信可能にして、再度登録してください。</p>
        <p><a href="{{ route('home') }}" class="btn btn-primary">マイページへ</a></p>
    </div>
</div>
@endsection
