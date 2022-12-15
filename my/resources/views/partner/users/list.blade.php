@extends('partner.layouts.partner')

@section('title', 'ユーザ管理')

@section('breadcrumbs')
{!!  Breadcrumbs::render('user.list')  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">ユーザ管理</h2>

    @include('partner.users.sections.search_user')

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
            <tr>
                <th class="w-25">ユーザ名</th>
                <th >ステータス</th>
                <th class="w-50">メモ</th>
                <th>ログ</th>
            </tr>
            </thead>
            <tbody class="table-hover">
            @foreach($list as $user)
                <tr @if($user->deleted_at)class="table-primary"@endif>
                    <td>
                        {{ link_to("user/$user->id", $user->nameWithEmail()) }}
                        @if($user->deleted_at)
                            <span class="badge badge-danger">削除済み</span>
                        @endif
                        @if($user->admin)
                            <span class="badge badge-warning">Admin</span>
                        @endif
                    </td>
                    <td>{{ $user->statusLabel() }}</td>
                    <td>{{ $user->memo }}</td>
                    <td>{{ link_to("user/log?search_user_id=$user->id", '表示', ['class' => 'btn btn-info']) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pull-right">
            {{ $list->appends($param)->links() }}
        </div>
    </div>
</div>
@endsection
