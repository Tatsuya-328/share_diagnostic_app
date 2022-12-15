@extends('auth.layouts.auth')
@section('title', "ログイン")
@section('page')
<div class="container mt-4">
    <div class="row">
        <div class="container">
            <div id="loginForm" class="authForm">
                <div class="col-md-8 offset-md-2 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-header text-center center-block brand"><img src="/images/logo.svg" alt="logo" width="150" height="75"></div>

                        <div class="card-body">
                            <form class="form-horizontal" method="POST" action="{{ route('login.exec') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <div class="row">
                                        <div class="col-12 col-lg-4">
                                            <label for="email" class="control-label">メールアドレス</label>
                                        </div>
                                        <div class="col-12 col-lg-8">
                                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                            @if ($errors->has('email'))
                                                <small class="text-danger">
                                                    {{ $errors->first('email') }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <div class="row">
                                        <div class="col-12 col-lg-4">
                                            <label for="password" class="control-label">パスワード</label>
                                        </div>
                                        <div class="col-12 col-lg-8">
                                            <input id="password" type="password" class="form-control" name="password" required>

                                            @if ($errors->has('password'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-6 offset-lg-4">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> ログイン情報を記憶
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                    <div class="row">
                                        <div class="col-12">
                                            @if ($errors->has('status'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('status') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                        <button type="submit" class="btn btn-primary col-12 col-lg-8 offset-lg-2">
                                            ログイン
                                        </button>
                                </div>
                                <div class="form-group">
                                    <div class="col-12">
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            パスワードを忘れた方
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="alert alert-warning fade show mt-3" role="alert">
                        <strong>注意!</strong>　こちらは会社関係者用のログイン画面です。会社関係者以外の方は登録をしないでください。
                    </div>
                </div>
            </div>
            <div class="ruleLinkBox col-md-8 offset-md-2 col-sm-12 col-xs-12">
                <a href="{{ route('join') }}" class="pull-right">新規登録はこちら</a>
            </div>
        </div>
    </div>
</div>
@endsection
