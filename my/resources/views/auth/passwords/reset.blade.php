@extends('auth.layouts.auth')
@section('title', "パスワード再登録")

@section('content')
<div class="container mt-4">
    <h2 class="h3">パスワード再登録</h2>
    <div class="mb-3">
        <form class="form-horizontal" method="POST" action="{{ route('password.reset.exec') }}">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="email">登録済みのメールアドレス</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="登録済みのメールアドレス" required>
                    </div>
                </div>
                <div class="col-md-12 col-lg-8 col-lg-offset-4">
                    @if ($errors->has('email'))
                        @foreach($errors->get('email') as $error)
                            <p class="text-danger">{{ $error }}</p>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="password">新しいパスワード</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input id="password" type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="パスワード（半角英数字8文字以上20文字以内）" aria-describedby="passwordHelp" required>
                        <small id="passwordHelp" class="form-text text-muted">8文字以上20文字以下の英数字どちらも含む文字列を入力してください</small>
                    </div>
                    <div class="col-md-12 col-lg-8 col-lg-offset-4">
                        @if ($errors->has('password'))
                            @foreach($errors->get('password') as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
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
                @if ($errors->has('password_confirmation'))
                    @foreach($errors->get('password_confirmation') as $error)
                        <p class="text-danger">{{ $error }}</p>
                    @endforeach
                @endif
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12 col-xs-12 text-center">
                        <input type="submit" class="btn btn-success" value="パスワードを再登録">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
