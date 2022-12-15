@extends('partner.layouts.partner')

@section('title', 'ユーザーログ')

@section('breadcrumbs')
{!!  Breadcrumbs::render('user.log')  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">ユーザーログ</h2>

    @include('partner.users.sections.search_log')

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
            <tr>
                <th>@sortablelink('created_at', '作成日', ['page' => $logs->currentPage()])</th>
                <th>@sortablelink('user_id', 'ユーザ',['page' => $logs->currentPage()])</th>
                <th>会社ID</th>
                <th class="w-50">アクション</th>
            </tr>
            </thead>
            <tbody class="table-hover">
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->created_at }}</td>
                    <td><a href="{{ route('user.detail', [$log->user->id]) }}">UserId:{{ $log->user_id }}</a><br><span class="badge badge-pill badge-secondary">{{ $log->user->name }}</span></td>
                    <td><a href="{{ route('companies.info', [$log->company_id]) }}">{{ $log->company_id }}</a></td>
                    <td>
                        <div class="badge badge-info">
                            @if (isset($actions[$log->action]))
                                {{ $actions[$log->action] }}
                            @else
                                {{$log->action}}
                            @endif
                        </div>
                        <div>
                            <textarea class="form-control text-primary" rows="3">{!! $log->getJsonToText() !!}</textarea>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pull-right">
            {{ $links }}
        </div>
    </div>


</div>
@endsection
