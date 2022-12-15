@extends('partner.layouts.partner')

@section('title', 'メールアドレス変更完了')

@section('content')
<div class="container">
    <h2 class="h3">メールアドレス変更</h2>
    @if (!empty($eMessage))
    <div class="alert alert-danger">
        <strong>エラー</strong>
        <p>{{ $eMessage }}</p>
    </div>
    @else
    <div class="mb-3">
        <p>メールアドレスの変更が完了しました。</p>
    </div>
    @endif
    <div class="text-center">
        <a href="{{ route('home') }}" class="btn btn-success">TOPへ</a>
    </div>
</div>
@endsection
