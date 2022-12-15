@extends('partner.layouts.partner')
@section('content')
<div class="container">
    <h2 class="h3">マイページ</h2>
    <div class="col-md-12">
        <div class="alert alert-info" style="word-wrap: break-word;">
            ようこそ、{{ Auth::user()->name }}さん
        </div>

        @if (isAdmin() && ($unprocessed_signup_badge || $processing_signup_badge || $unprocessed_update_badge || $processing_update_badge || $unprocessed_delete_badge || $processing_delete_badge))
            <div class="card mb-3">
                <div class="card-header">申請項目</div>
                <div class="card-body">
                    @if ($unprocessed_signup_badge)
                        <a href="/companies?search_authorize_status=unprocessed&search_request_type=signup">
                            <button type="button" class="btn btn-success my-1">新規申請（未処理） <span class="badge badge-light">{{ $unprocessed_signup_badge }}</span></button>
                        </a>
                    @endif
                    @if ($processing_signup_badge)
                        <a href="/companies?search_authorize_status=processing&search_request_type=signup">
                            <button type="button" class="btn btn-outline-success my-1">新規申請（処理中） <span class="badge badge-light">{{ $processing_signup_badge }}</span></button>
                        </a>
                    @endif
                    @if ($unprocessed_update_badge)
                        <a href="/companies?search_authorize_status=unprocessed&search_request_type=update">
                            <button type="button" class="btn btn-warning my-1">変更申請（未処理） <span class="badge badge-light">{{ $unprocessed_update_badge }}</span></button>
                        </a>
                    @endif
                    @if ($processing_update_badge)
                        <a href="/companies?search_authorize_status=processing&search_request_type=update">
                            <button type="button" class="btn btn-outline-warning my-1">変更申請（処理中） <span class="badge badge-light">{{ $processing_update_badge }}</span></button>
                        </a>
                    @endif
                    @if ($unprocessed_delete_badge)
                        <a href="/companies?search_authorize_status=unprocessed&search_request_type=delete">
                            <button type="button" class="btn btn-danger my-1">削除申請（未処理） <span class="badge badge-light">{{ $unprocessed_delete_badge }}</span></button>
                        </a>
                    @endif
                    @if ($processing_delete_badge)
                        <a href="/companies?search_authorize_status=processing&search_request_type=delete">
                            <button type="button" class="btn btn-outline-danger my-1">削除申請（処理中） <span class="badge badge-light">{{ $processing_delete_badge }}</span></button>
                        </a>
                    @endif
                </div>
            </div>
        @endif
        <ul class="list-group">
            <li class="list-group-item">{{ link_to('companies', '会社') }}</li>
            <li class="list-group-item">{{ link_to('user/edit', '会員情報変更') }}</li>
        @if (isAdmin())
            <li class="list-group-item">{{ link_to('user', 'ユーザ(PCのみ)') }}</li>
            <li class="list-group-item">{{ link_to('style', 'スタイル(PCのみ)') }}</li>
            <li class="list-group-item">{{ link_to('facility', '条件(PCのみ)') }}</li>
            <li class="list-group-item">{{ link_to('user/log', 'ユーザーログ(PCのみ)') }}</li>
        @endif
        </ul>
    </div>
</div>
@endsection
