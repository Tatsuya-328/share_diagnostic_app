@extends('auth.layouts.auth')
@section('title', "会員登録エラー")
@section('content')
<div class="container mt-4">
    <h2 class="h3">会員登録</h2>
    <div class="eGroup">
        @if (!empty($eMessage))
            <div class="alert alert-danger">
                <strong>エラー</strong>
                <p>{{ $eMessage }}</p>
            </div>
        @endif
        <div class="text-center">
            <p><a href="{{ route('join') }}" class="btn btn-success">会員登録ページへ</a></p>
        </div>
    </div>
</div>

@endsection