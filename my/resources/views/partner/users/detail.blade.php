@extends('partner.layouts.partner')

@section('title', 'ユーザ管理')

@section('breadcrumbs')
{!!  Breadcrumbs::render('user.detail', $user)  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">ユーザ詳細</h2>
    @php
        $border_color = 'border-success';
        // 退会
        if ( $user->deleted_at || $user->status == 'leave') {
            $border_color = 'border-danger';
        // 招待中
        } elseif ($user->status == 'invite') {
            $border_color = 'border-warning';
        // 仮登録
        } elseif ($user->status == 'provisional') {
            $border_color = 'border-secondary';
        }
    @endphp

    <form method="POST" action="/user/{{ $user->id }}" onsubmit='if (confirm("このアカウントのステータスを変更します。よろしいですか？")) { return true; } else { return false; }'>
        {{ csrf_field() }}
        <div class="row">
            <div class="col-sm-12 col-md-8">
                <div class="card mb-3 {{ $border_color }}">
                    <div class="card-header">
                        <b>{{ $user->name }}</b>
                        @if($user->deleted_at)
                            <span class="badge badge-danger">削除済み</span>
                        @endif
                        @if($user->admin)
                            <span class="badge badge-warning">Admin</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <h4 class="card-title h5">詳細情報</h4>
                        <p class="card-text"><span class="badge badge-light">ステータス</span> {{ $user->statusLabel() }}{{ $user->is_ban ? '（停止中）' : '' }}</p>
                        <p class="card-text"><span class="badge badge-light">メールアドレス</span> {{ $user->email }}</p>
                        <p class="card-text"><span class="badge badge-light">メールアドレス変更中</span> {{ $user->change_email ? $user->change_email : 'なし' }}</p>
                        <p class="card-text">{{ link_to("user/log?search_user_id=$user->id", 'ログ表示', ['class' => 'btn btn-info']) }}</p>

                        <h4 class="card-title h5">所属会社一覧（予定）</h4>
                        <p class="card-text"></p>

                    </div>
                    <div class="card-footer text-muted">
                        @if ($user->is_ban)
                        <button name="btn-ban" type="submit" class="btn btn-success">アカウント復帰</button>
                        @else
                        <button name="btn-ban" type="submit" class="btn btn-danger">アカウント停止</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="card mb-3 {{ $border_color }}">
                    <div class="card-body">
                        <h4 class="card-title h5">運用メモ</h4>
                        <p class="card-text">
                            {{ Form::textarea('memo', old('memo') ? old('memo') : $user->memo, ['class' => 'form-control']) }}
                        </p>
                        @if ($errors->has('memo'))
                        <p class="has-error">
                            <span class="help-block">
                                <strong>{{ $errors->first('memo') }}</strong>
                            </span>
                        </p>
                        @endif
                        <p>
                            <button name="btn-save" type="submit" class="btn btn-primary">保存</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
