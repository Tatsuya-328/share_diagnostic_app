@extends('partner.layouts.partner')
@section('title', sprintf("%sスタイル並び替え", $company->name))

@section('breadcrumbs')
  {!!  Breadcrumbs::render('companies.styles_order', $company)  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">会社登録情報 <small class="text-muted">スタイル並び替え設定</small></h2>
    <div class="card my-3">
        <div class="card-body">
            <h4 class="card-title h6">重視しているスタイルの順番に並び替えてください</h4>
            {{ Form::open(['route' => ['companies.styles.order.exec', $company->id], 'method'=>'patch', 'id'=>'company_form', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data', 'onsubmit' => "return submit_hiddens();"]) }}
            <div class="row">
                @if ($isMobile)
                    <div class="col-12">
                        <div class="form-group">
                            <ul class="text-center list-group" id="sortable" >
                                @foreach($company->styles as $style)
                                    <li class="list-group-item" data-id="{{ $style->id }}">{{ $style->name() }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-12">
                        <small class="form-text text-muted">ドラッグ&ドロップを行って要素を並び替えて下さい</small>
                    </div>
                @else
                    <div class="subject-info-box">
                        <div class="selected-left">
                            <select multiple="" class="form-control" id="to_box">
                            @foreach ($company->styles as $style)
                                <option value="{{ $style->id }}"{{ $style->deleted_at ? ' class=deleted' : '' }}>{{ $style->name() }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="selected-right">
                            <button type="button" class="btn btn-secondary btn-sm" id="item_up">
                                <i class="fa fa-angle-up" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" id="item_down">
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                @endif
            </div>
                <div class="save text-center my-3">
                    <button type="submit" class="btn btn-success">保存する</button>
                </div>
                <div id="hidden_items"></div>
            {{ Form::close() }}
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
    @endif
@endsection
