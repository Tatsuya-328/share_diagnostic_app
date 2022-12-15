@extends('partner.layouts.partner')

@section('title', '会社編集メンバー')

@section('breadcrumbs')
{!!  Breadcrumbs::render('companies.users', $company)  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">会社登録情報 <small class="text-muted">編集メンバー</small></h2>
    <div class="card my-3">
        <div class="card-body">
            <h5 class="card-title">メンバー一覧</h5>
            <div class="my-3">
                <form method="POST" action="/companies/{{ $company->id }}/users/remove" onsubmit="return confirm('本当に削除しますか？')">
                    {{ csrf_field() }}
                    <ul class="list-group">
                        @forelse($list as $record)
                            <li class="list-group-item">
                                @if ($record->user->isStatusMember())
                                {{ $record->user->name }}{{ $record->user->is_ban ? '（停止中）' : '' }}
                                @else
                                {{ $record->user->email }}{{ '（' . $record->user->statusLabel() . '）' }}
                                @endif
                                <button name="btn-remove" type="submit" class="float-right btn btn-danger" value="{{ $record->user_id }}">削除</button>
                            </li>
                        @empty
                        <li class="list-group-item">
                            他のメンバーはいません
                        </li>
                        @endforelse
                    </ul>
                </form>
            </div>
            <h5 class="card-title">新しくメンバーを招待する</h5>
            <form method="POST" action="/companies/{{ $company->id }}/users/invite">
                {{ csrf_field() }}
                <div class="my-3">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 col-lg-8">
                                {{ Form::email('email', old('email'), ['class' => 'form-control mb-3', 'placeholder' => '招待したい人のメールアドレス']) }}
                                @if ($errors->has('email'))
                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                @endif
                            </div>
                            <div class="col-12 col-lg-4">
                                <button name="btn-invite" type="submit" class="btn btn-primary">招待メールを送信</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
