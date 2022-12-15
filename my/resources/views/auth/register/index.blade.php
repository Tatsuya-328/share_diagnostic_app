@extends('auth.layouts.auth')
@section('title', "会員登録")
@section('content')
<div class="container mt-4">
    <h2 class="h3">会員登録</h2>

    <div id="registerForm" class="authForm">
        @if ($errors->has('name') || $errors->has('email') || $errors->has('password'))
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>入力エラー</strong>
            </div>
        @endif
        <div class="mb-3">
            <form class="form-horizontal" method="POST" action="{{ route('provisional') }}">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <label class="control-label" for="name">ご担当者様のニックネーム</label>
                        </div>
                        <div class="col-12 col-lg-8">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="ニックネーム" required autofocus>
                            @if ($errors->has('name'))
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <label class="control-label" for="email">ご担当者様のメールアドレス</label>
                        </div>
                        <div class="col-12 col-lg-8">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="受信可能なメールアドレス" required>
                            @if ($errors->has('email'))
                                <p class="text-danger">{{ $errors->first('email') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <label class="control-label" for="password">パスワード</label>
                        </div>
                        <div class="col-12 col-lg-8">
                            <input id="password" type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="パスワード" aria-describedby="passwordHelp" required>
                            <small id="passwordHelp" class="form-text text-muted">8文字以上20文字以下の英数字どちらも含む文字列を入力してください</small>
                            @if ($errors->has('password'))
                                <p class="text-danger">{{ $errors->first('password') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <label class="control-label" for="password_confirmation">パスワードの入力確認</label>
                        </div>
                        <div class="col-12 col-lg-8">
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="パスワードの再入力" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-center">
                            <input type="submit" class="btn btn-success" value="この内容で会員登録をする">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="form-group">
            <div class="text-center">
                <a href="{{ route('login') }}">すでに会員の方はこちら</a>
            </div>
        </div>
    </div>
</div>
@endsection
