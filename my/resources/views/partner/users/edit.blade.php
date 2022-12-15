@extends('partner.layouts.partner')

@section('title', '会員情報変更')

@section('breadcrumbs')
{!!  Breadcrumbs::render('user.edit')  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">会員情報変更</h2>

    @if ($errors->has('name') || $errors->has('password'))
        <div class="alert alert-danger">
            <strong>入力エラー</strong>
        </div>
    @endif
    <div class="mb-3">
        <h4 class="h6">変更が必要な項目のみ入力してください</h4>
        <form class="form-horizontal" method="POST" action="{{ route('user.edit') }}">
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="name">ニックネーム</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') ? old('name') : $user->name }}" placeholder="ニックネーム" required autofocus>
                        @if ($errors->has('name'))
                            <p class="text-danger">{{ $errors->first('name') }}</p>
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
                        <input id="password" type="password" class="form-control" name="password" placeholder="半角英数字8文字以上" aria-describedby="passwordHelp">
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
                        <label for="password-confirm" class="control-label">パスワードの入力確認</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="パスワードの再入力">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">この内容で保存</button>
                </div>
            </div>
        </form>
    </div>
    <div class="form-group">
        <div class="text-center">
            {{ link_to('user/email', 'メールアドレスの変更はこちら') }}
        </div>
    </div>
    <div class="form-group">
        <div class="text-center">
            {{ link_to('user/leave', '退会はこちら') }}
        </div>
    </div>
</div>
@endsection
