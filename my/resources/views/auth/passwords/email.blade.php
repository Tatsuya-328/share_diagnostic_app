@extends('auth.layouts.auth')

@section('title', "パスワード再登録")

@section('content')
<div class="container mt-4">
    <h2 class="h3">パスワード再登録</h2>
    <div class="col-lg-12">
        <div class="card border-danger mb-3">
            <div class="card-header">登録していたメールアドレスをご入力ください</div>
            <div class="card-body">
                <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label">メールアドレス</label>

                        <div class="col-md-6">
                            <input id="email" type="email" placeholder="メールアドレス" class="form-control" name="email" value="{{ old('email') }}" required email>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                </span>
                            @endif
                        </div>
                        @if (session('status'))
                            <div class="invalid-feedback">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                リセット用リンクを送信
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
