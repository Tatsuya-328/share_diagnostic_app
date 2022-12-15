@extends('partner.layouts.partner')

@section('title', sprintf("%sスタイル編集", $company->name))

@section('breadcrumbs')
    {!!  Breadcrumbs::render('companies.styles', $company)  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">会社登録情報 <small class="text-muted">スタイル設定</small></h2>
    <div class="card my-3">
        <div class="card-body">
            <h4 class="card-title h6">会社で教えているスタイルをすべて選択してください</h4>
            {{ Form::open(['route' => ['companies.styles.exec', $company->id], 'method'=>'patch', 'id'=>'company_form', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data']) }}
                <div class="form-group">
                    @foreach($styles as $style)
                        @php
                            $isCheck = false;
                            $companyStyles = setFormValue("styles", $company);
                            if (!empty($companyStyles)) {
                                foreach (setFormValue("styles", $company) as $companyStyle) {
                                    if ($style->id == $companyStyle->id) {
                                        $isCheck = true;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" name="styles[]" value="{{ $style->id }}" id="customCheck{{ $style->id }}" @if($isCheck) checked @endif>
                            <label class="custom-control-label" for="customCheck{{ $style->id }}">{{ $style->name() }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        @if ($errors->has('styles'))
                            @foreach($errors->get('styles') as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="form-group text-center">
                    <div class="col-md-12">
                        <button class="btn btn-success">保存して並び替え</button>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
    @parent
@endsection

@section('scripts')
    @parent
@endsection