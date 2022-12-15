@extends('partner.layouts.partner')

@section('title', 'メールアドレス変更')

@section('breadcrumbs')
{!!  Breadcrumbs::render('user.email')  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">メールアドレス変更</h2>
    <div class="mb-3">
        <form class="form-horizontal" method="POST" action="/user/email">
            {{ csrf_field() }}
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label">設定しているメールアドレス</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <label class="control-label">{{ $user->email }}</label>
                    </div>
                </div>
            </div>
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label for="email" class="control-label">新しいメールアドレス</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                        @if ($errors->has('email'))
                            <p class="text-danger">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">認証メールを送信</button>
                </div>
            </div>
            <div class="text-center">
                <a href="/user/edit" class="btn btn-secondary">戻る</a>
            </div>
        </form>
    </div>
</div>
@endsection
