@extends('partner.layouts.partner')

@section('title', "会社一覧")

@section('breadcrumbs')
{!!  Breadcrumbs::render('companies')  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">会社一覧</h2>

    @if (!empty($user->admin))
        @include('partner.companies.sections.search')
    @endif
    @if(count($companies))
        <div class="">
            <ul class="list-group">
            @foreach($companies as $company)
                @php
                    $companyId = $company->id;
                    $status = $statuses[$company->status];
                    $name = $company->name;
                    $updatedAt = $company->updated_at;
                    if (!empty($company->companyChangeRequest)) {
                        $name = $company->companyChangeRequest->name;
                        $authorizeStatus = $authorize_statuses[$company->companyChangeRequest->authorize_status];
                        $requestType = $request_types[$company->companyChangeRequest->request_type];
                        $updatedAt = $company->companyChangeRequest->updated_at;
                    } else {
                        $authorizeStatus = "承認済み";
                        $requestType = "";
                    }
                    $labels = companyStatusLabel($company->status, !empty($company->companyChangeRequest) ? $company->companyChangeRequest->request_type : null, !empty($company->companyChangeRequest) ? $company->companyChangeRequest->
                        authorize_status : null)
                @endphp
                <li class="list-group-item">
                    <div class="col-12">
                        <b>{{ $name }}</b>
                    </div>
                    <div class="col-12">
                        <span class="badge badge-{{ $labels['status']['style'] }}">
                            {{ $labels['status']['label'] }}
                        </span>
                        @if (!empty($labels['requestType']))
                             <span class="badge badge-{{ $labels['requestType']['style'] }}">
                                {{ $labels['requestType']['label'] }}
                            </span>
                        @endif
                        @if (!empty($labels['authorizeStatus']))
                             <span class="badge badge-{{ $labels['authorizeStatus']['style'] }}">
                                {{ $labels['authorizeStatus']['label'] }}
                            </span>
                        @endif
                        @if($company->isProcessiong() && isAdmin())
                            <span class="text-info text-warning">{{ $company->companyChangeRequest->admin->name }}さんが審査中</span>
                        @endif
                    </div>
                    <div class="col-12 clearfix">
                        <div class="float-left align-self-end align-bottom">更新日:{{ $updatedAt }}</div>
                        <a class="float-right btn btn-info" href="{{ route('companies.info', [$companyId]) }}" data-toggle="tooltip" data-placement="top">詳細へ</a>
                    </div>
                </li>
            @endforeach
            </ul>
        </div>
        <div class="pull-right my-3">
            {{ $links }}
        </div>
    @else
        <div class="well">
            <p>現在管理している会社はございません。</p>
        </div>
    @endif
    <div class="pull-left my-3">
        <a class="btn btn-success" href="{{ route('companies.create') }}">新規作成</a>
    </div>
</div>
@endsection

@section('styles')
    @parent
@endsection

@section('scripts')
    @parent
@endsection
