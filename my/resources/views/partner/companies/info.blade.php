@extends('partner.layouts.partner')
@section('meta')
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
@endsection
@section('breadcrumbs')
    {!!  Breadcrumbs::render('companies.info', $company)  !!}
@endsection
@section('title', sprintf("%s詳細", $company->name))


@section('content')
<div class="container">
    <h2 class="h3">会社登録情報</h2>
    <div class="bs-component">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#basic-info" data-toggle="tab">基本情報</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">詳細情報</a>
                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 42px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <a class="dropdown-item" href="#detail" data-toggle="tab">詳細情報</a>
                    <a class="dropdown-item" href="#access" data-toggle="tab">アクセス</a>
                    <a class="dropdown-item" href="#style" data-toggle="tab">スタイル / 条件</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#company-member" data-toggle="tab">編集メンバー</a>
            </li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active show" id="basic-info">
                <div class="my-3">
                    <h5 class="card-title">基本情報</h5>

                    @can('editBase', $company)
                        @if ($company->isShowMessage())
                            <div class="alert alert-danger" role="alert">
                                <h5 class="alert-heading">申請が却下されました</h5>
                                <p>下記の却下理由をご確認の上、修正が必要な場合は編集を行い再申請をおこなってください</p>
                                <hr>
                                {!! showText($company->companyChangeRequest->message )!!}
                            </div>
                        @endif
                    @endcan

                    <ul class="list-group">
                        <li class="list-group-item">
                            @php
                                $labels = companyStatusLabel($company->status, !empty($company->companyChangeRequest) ? $company->companyChangeRequest->request_type : null, !empty($company->companyChangeRequest) ? $company->companyChangeRequest->
                                    authorize_status : null)
                            @endphp
                            <div>
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
                            </div>
                            <div>{{ $company->name }}</div>
                            @if($company->isRequestChanged('name'))
                                <div class="badge badge-pill badge-warning">変更後</div>
                                <div class="text-danger">{{ $company->companyChangeRequest->name }}</div>
                            @endif
                            @if($company->isProcessiong() && isAdmin())
                                <span class="text-info text-warning">{{ $company->companyChangeRequest->admin->name }}さんが審査中</span>
                            @endif
                        </li>
                        <li class="list-group-item">
                            <legend class="h6"><i class="fa fa-home"></i>住所</legend>
                            <div>〒{{ $company->convertedAddress() }}</div>
                            @if($company->isRequestChanged('convertedAddress', true))
                                <div class="badge badge-pill badge-warning">変更後</div>
                                <div class="text-danger">〒{{ $company->companyChangeRequest->convertedAddress() }}</div>
                            @endif
                            @if (isAdmin() && $company->notHasCityId())
                                <div class="text-danger">市区町村が検出できないため市区町村の検索結果に表示できません。住所を見直してください。</div>
                            @endif
                            @if (!empty($company->lat) && !empty($company->lon))
                                @if (isAdmin())
                                    <div  class="my-3">
                                        <div>緯度:{{ $company->lat }} 経度:{{ $company->lon }}</div>
                                        @if($company->isRequestChanged('lat') || $company->isRequestChanged('lon'))
                                            <div class="badge badge-pill badge-warning">変更後</div>
                                            <div class="text-danger">@if($company->isRequestChanged('lat'))緯度:{{ $company->companyChangeRequest->lat }}@endif @if($company->isRequestChanged('lon'))経度:{{ $company->companyChangeRequest->lon }}@endif</div>
                                        @endif
                                    </div>
                                @endif
                                <div class="row google-map">
                                    <iframe frameborder="0" src="https://www.google.com/maps/embed/v1/place?key={{ Config::get('const.googleMapApiKey') }}&q={{ $company->current()->lat }},{{ $company->current()->lon }}" allowfullscreen></iframe>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 carousel-inner" id="map"  data-lat="{{ $company->current()->lat }}" data-lon="{{ $company->current()->lon }}" ></div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <legend class="h6"><i class="fa fa-phone "></i>電話番号</legend>
                            <div>{{ $company->tel_num }}</div>
                            @if($company->isRequestChanged('tel_num'))
                                <div class="badge badge-pill badge-warning">変更後</div>
                                <div class="text-danger">{{ $company->companyChangeRequest->tel_num }}</div>
                            @endif
                        </li>
                        <li class="list-group-item">
                            <legend class="h6"><i class="fa fa-pencil "></i>会社紹介文</legend>
                            <div>
                                {!! showText($company->description) !!}
                            </div>
                            @if($company->isRequestChanged('description'))
                                <div class="badge badge-pill badge-warning">変更後</div>
                                <div class="text-danger">{!! showText($company->companyChangeRequest->description) !!}</div>
                            @endif
                        </li>
                    </ul>
                    @can('editBase', $company)
                        <div class="text-left m-2"><a class="btn btn-primary" href="{{ route('companies.base', [$company->id]) }}">編集する</a></div>
                    @endcan
                </div>
                <div class="mt-5 mb-3">
                    <h5>画像</h5>
                    @if ($company->isRequestChanged("image_ids"))
                        <div class="col-md-12">
                            <p><strong>変更前</strong></p>
                        </div>
                    @endif
                    <div class="companyImgListArea">
                        @if (!empty($company->convertedImages()))
                            <ul class="card-group pl-3">
                                @foreach($company->convertedImages() as $image)
                                    <li class="card my-1">
                                        <img src="{{ getCdnImagePath($image->filepath) }}" class="card-img-top" id="mainImage" />
                                        <div class="card-body imgDescription">
                                            <div class="badge badge-pill badge-{{ $loop->first ? 'dark' : 'light' }}">画像{{ $loop->iteration }}</div>
                                            <div>{{ $image->alt }}</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>設定されていません</p>
                        @endif
                    </div>
                    @if ($company->isRequestChanged("image_ids"))
                        <div class="col-md-12">
                            <p><strong>変更後</strong></p>
                        </div>
                    @endif
                    {{-- 公開後に基本情報の画像変更があった場合 --}}
                    @if ($company->isRequestChanged("image_ids"))
                        <div class="companyImgListArea">
                            @php
                                $images = $company->companyChangeRequest->convertedImages();
                            @endphp
                            @if (!empty($images))
                                <ul class="card-group pl-3">
                                    @foreach($images as $image)
                                        <li class="card my-1">
                                            <img src="{{ getCdnImagePath($image->filepath) }}" class="card-img-top" id="mainImage" />
                                            <div class="card-body imgDescription">
                                                <div class="badge badge-pill badge-{{ $loop->first ? 'dark' : 'light' }}">画像{{ $loop->iteration }}</div>
                                                <div>{{ $image->alt }}</div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>設定されていません</p>
                            @endif
                        </div>
                    @endif
                    @can('editBase', $company)
                        <div class="text-left m-2"><a class="btn btn-primary" href="{{ route('companies.images', [$company->id]) }}">画像を編集</a></div>
                    @else
                        @if (!isAdmin())
                            <p class="text-warning">会社の審査中のため、編集出来ません</p>
                        @endif
                    @endcan
                </div>
                @include('partner.companies.sections.apply')
            </div>
            <div class="tab-pane fade" id="detail">
                <div class="my-3">
                    <h5>詳細情報</h5>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <legend class="h6">営業時間</legend>
                            {!! showText($company->business_hours) ?? "設定されていません" !!}
                        </li>
                        <li class="list-group-item">
                            <legend class="h6">定休日</legend>
                            {!! showText($company->holiday) ?? "設定されていません" !!}
                        </li>
                        <li class="list-group-item">
                            <legend class="h6">入会金</legend>
                            {!! showText($company->admission_fee) ?? "設定されていません" !!}
                        </li>
                        <li class="list-group-item">
                            <legend class="h6">基本料金</legend>
                            {!! showText($company->base_fee) ?? "設定されていません" !!}
                        </li>
                        <li class="list-group-item">
                            <legend class="h6">レンタル情報</legend>
                            {!! showText($company->rental_description) ?? "設定されていません" !!}
                        </li>
                        <li class="list-group-item">
                            <legend class="h6">体験レッスン説明</legend>
                            {!! showText($company->trial_description) ?? "設定されていません" !!}
                        </li>
                        <li class="list-group-item">
                            <legend class="h6">会社名</legend>
                            {!! showText($company->company_name) ?? "設定されていません" !!}
                        </li>
                        <li class="list-group-item">
                            <legend class="h6">ホームページ</legend>
                            @if(!empty($company->url))
                                <a href="{{ $company->url }}" target="_blank" class="word-break">{{ $company->url }}</a> <i class="fa fa-external-link text-muted"></i>
                            @else
                                設定されていません
                            @endif
                        </li>
                        <li class="list-group-item">
                            <legend class="h6">Facebook</legend>
                            @if(!empty($company->facebook))
                                <a href="https://facebook.com/{{ $company->facebook }}" target="_blank" class="word-break">{{ '@' . str_replace('/', '', $company->facebook) }}</a> <i class="fa fa-external-link text-muted"></i>
                            @else
                                設定されていません
                            @endif
                        </li>
                        <li class="list-group-item">
                            <legend class="h6">Instagram</legend>
                            @if(!empty($company->instagram))
                                <a href="https://www.instagram.com/{{ $company->instagram }}" target="_blank" class="word-break">{{ '@'.$company->instagram }}</a> <i class="fa fa-external-link text-muted"></i>
                            @else
                                設定されていません
                            @endif
                        </li>
                        <li class="list-group-item">
                            <legend class="h6">Twitter</legend>
                            @if(!empty($company->twitter))
                                <a href="https://twitter.com/{{ $company->twitter }}" target="_blank" class="word-break">{{ '@'.$company->twitter }}</a> <i class="fa fa-external-link text-muted"></i>
                            @else
                                設定されていません
                            @endif
                        </li>
                    </ul>
                    @can('edit', $company)
                        <div class="text-left m-2"><a class="btn btn-primary" href="{{ route('companies.detail1', [$company->id]) }}">編集する</a></div>
                    @endcan
                </div>
            </div>
            <div class="tab-pane fade" id="access">
                <div class="my-3">
                    <h5>最寄駅</h5>
                    <ul class="list-group">
                        @if(!empty($company->stations) && count($company->stations))
                            @foreach($company->stations as $station)
                                <li class="list-group-item">
                                    {{ $station->name }}駅
                                </li>
                            @endforeach
                        @else
                            <li class="list-group-item">最寄駅が設定されていません</li>
                        @endif
                    </ul>
                    <h5 class="my-3">アクセス</h5>
                    <ul class="list-group">
                        @if (!empty($company->address_description))
                            <li class="list-group-item">{!! showText($company->address_description) !!}</li>
                        @else
                            <li class="list-group-item">設定されていません</li>
                        @endif
                    </ul>
                    <div class="text-left m-2"><a class="btn btn-primary" href="{{ route('companies.detail2', [$company->id]) }}">編集する</a></div>
                </div>
            </div>
            <div class="tab-pane fade" id="style">
                <div class="my-3">
                    <h5>スタイル</h5>
                    <ul class="list-group">
                        <li class="list-group-item">
                        @if (!empty($company->styles) && !empty(count($company->styles)))
                            @foreach($company->styles as $style)
                                <span class="badge badge-success">{{ $style->name() }}</span>
                            @endforeach
                        @else
                            <p>設定されていません</p>
                        @endif
                        </li>
                    </ul>
                    <div class="text-left m-2"><a class="btn btn-primary" href="{{ route('companies.styles', [$company->id]) }}">編集する</a></div>
                </div>
                <div class="mt-5 mb-3">
                    <h5>条件</h5>
                    <ul class="list-group">
                        <li class="list-group-item">
                        @php
                            $facilityIdList = [];
                        @endphp
                        @if (!empty($company->companyfacilities) && !empty(count($company->companyfacilities)))
                            @foreach($company->companyfacilities as $companyfacility)
                                <span class="badge badge-success">{{ $companyfacility->name }}</span>
                                @php
                                    $facilityIdList[] = $companyfacility->id;
                                @endphp
                            @endforeach
                        @endif
                        @foreach($companyfacilities as $companyfacility)
                            @if (!in_array($companyfacility->id, $facilityIdList))
                                <span class="badge badge-dark">{{ $companyfacility->name }}</span>
                            @endif
                        @endforeach
                        </li>
                    </ul>
                    <div class="text-left m-2"><a class="btn btn-primary" href="{{ route('companies.search', [$company->id]) }}">編集する</a></div>
                </div>
             </div>
            <div class="tab-pane fade" id="company-member">
                <div class="my-3">
                    <h5>編集メンバー</h5>
                    <ul class="list-group">
                        @forelse($company->usersCompaniesExceptAdmin() as $users_companies)
                            <li class="list-group-item">
                            @if ($users_companies->user->isStatusMember())
                                {{ $users_companies->user->name }}{{ $users_companies->user->is_ban ? '（停止中）' : '' }}
                            @else
                                {{ $users_companies->user->email }}{{ '（' . $users_companies->user->statusLabel() . '）' }}
                            @endif
                            </li>
                        @empty
                            <li class="list-group-item">メンバーはいません</li>
                        @endforelse
                    </ul>
                    @if (Auth::user()->admin_id || Auth::user()->isCompanyOwner($company->id))
                    <div class="text-left m-2"><a class="btn btn-primary" href="{{ route('companies.users', [$company->id]) }}">編集する</a></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script src="{{ mix('assets/partner/js/companies/info.js') }}"></script>
@endsection

