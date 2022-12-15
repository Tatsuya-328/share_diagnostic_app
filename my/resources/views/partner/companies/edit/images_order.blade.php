@extends('partner.layouts.partner')
@section('title', sprintf("%s画像並び替え | %s", $company->name, config('const.appName')))

@section('breadcrumbs')
  {!!  Breadcrumbs::render('companies.images_order', $company)  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">会社登録情報 <small class="text-muted">画像並び替え</small></h2>
    <div class="card my-3">
        <div class="card-body">
            @php
                $images = $company->currentImages();
            @endphp
            @if (count($images))
                {{ Form::open(['route' => ['companies.images.order.exec', $company->id], 'method'=>'patch', 'id'=>'company_form', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data', 'onsubmit' => "return submit_hiddens();"]) }}
                <div class="row">
                    @if ($isMobile)
                        <div class="col-12">
                            <div class="form-group">
                                <ul class="text-center list-group" id="sortable" >
                                    @foreach($images as $image)
                                        <li class="list-group-item" data-id="{{ $image->id }}">
                                            <div class="row">
                                                <div class="col-4"><img src="{{getCdnImagePath($image->filepath)}}" id="mainImage" width="100%" style="margin: -0.75rem -1.25rem;" /></div>
                                                <div class="col-8">{{ $image->alt }}</div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-12">
                            <small class="form-text text-muted">ドラッグ&ドロップを行って要素を並び替えて下さい</small>
                        </div>
                    @else
                        <div class="image-info-box col-12">
                            <div class="selected-left">
                                <select multiple="" class="form-control" id="to_box">
                                @foreach ($images as $image)
                                    <option value="{{ $image->id }}">{{ $image->alt }}</option>
                                @endforeach
                                </select>
                                @foreach ($images as $image)
                                    <div style="display: none;" id="imageForm{{ $image->id }}">
                                        <img src="{{ getCdnImagePath($image->filepath) }}" class="imageOption">
                                    </div>
                                @endforeach
                            </div>
                            <div class="selected-right">
                                <div class="float-left mr-1">
                                <button type="button" class="btn btn-secondary btn-sm" id="item_up">
                                    <i class="fa fa-angle-up" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" id="item_down">
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </button>
                                </div>
                                <div class="float_right">
                                    <div class="card border-dark mb-3">
                                        <div class="card-header">選択中の画像</div>
                                        <div id="showImage" class="card-body" width="300px"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    @endif
                </div>
                <div class="save text-center my-3">
                    <button type="submit" class="btn btn-primary">保存する</button>
                </div>
                <div id="hidden_items"></div>
                {{ Form::close() }}
            @else
                <p>画像が設定されていません</p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    @if ($isMobile)
        <script src="{{ mix('assets/partner/js/common/edit_order_mobile.js') }}"></script>
    @else
        <script src="{{ mix('assets/partner/js/common/edit_order.js') }}"></script>
        <script src="{{ mix('assets/partner/js/companies/edit_image_order.js') }}"></script>
    @endif
@endsection
