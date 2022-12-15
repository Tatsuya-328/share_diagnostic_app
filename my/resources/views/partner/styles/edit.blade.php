@extends('partner.layouts.partner')

@section('title', 'スタイル管理')

@section('breadcrumbs')
{!!  Breadcrumbs::render('style.edit')  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">スタイル管理 <small class="text-muted">編集・並び替え</small></h2>
    <div class="col-12">
        <div class="alert alert-dismissible alert-warning">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <p class="mb-0">「心理テストの種類」または「心理テスト用語」から会社検索で使用するスタイルを選択してください。</p>
            <p class="mb-0">「心理テストの種類」と「心理テスト用語」どちらにもスタイルが登録してある場合は、「心理テストの種類」を優先してください。</p>
        </div>
    </div>
    <div class="col-md-12">
        <div class="subject-info-box-1">
            <div>
                <label>心理テストの種類</label>
                {{ Form::select('yg_types', $yg_types, null, ['multiple' => '', 'class' => 'form-control', 'id' => 'from_box1']) }}
            </div>
            <div style="margin-top:10px">
                <label>心理テスト用語</label>
                {{ Form::select('yg_words', $yg_words, null, ['multiple' => '', 'class' => 'form-control', 'id' => 'from_box2']) }}
            </div>
        </div>

        <div class="subject-info-arrows text-center">
            <div class="arrow-right">
                <button type="button" class="btn btn-secondary btn-sm border" id="add_box1">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                </button>
            </div>
            <div class="arrow-right">
                <button type="button" class="btn btn-secondary btn-sm border" id="add_box2">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <form method="POST" action="/style/edit" onsubmit="return submit_hiddens();">
        {{ csrf_field() }}
        <div class="subject-info-box-2">
            <label>スタイル一覧</label>
            <div>
                <div class="selected-left">
                    <select multiple="" class="form-control" id="to_box">
                    @foreach ($list as $style)
                        <option value="{{ $style->optionValue() }}"{{ $style->deleted_at ? ' class=deleted' : '' }}>
                            {{ $style->name() }}
                            @if (preg_match("/^1_/", $style->optionValue()))
                                （種類）
                            @elseif (preg_match("/^2_/", $style->optionValue()))
                                （用語）
                            @else
                                （その他）
                            @endif
                        </option>
                    @endforeach
                    </select>
                </div>
                <div class="selected-right">
                    <button type="button" class="btn btn-secondary btn-sm border" id="item_up">
                        <i class="fa fa-angle-up" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm border" id="item_down">
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="float-right save m-3">
            <button type="submit" class="btn btn-primary">保存</button>
        </div>

        <div id="hidden_items"></div>
        </form>
    </div>
</div>
@endsection

@section('styles')
    @parent
@endsection

@section('scripts')
    @parent
    <script src="{{ mix('assets/partner/js/common/edit_order.js') }}"></script>
@endsection
