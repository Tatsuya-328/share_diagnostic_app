@extends('partner.layouts.partner')

@section('title', sprintf("%s検索条件編集", $company->name))

@section('breadcrumbs')
    {!!  Breadcrumbs::render('companies.search', $company)  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">会社登録情報 <small class="text-muted">条件設定</small></h2>
    <div class="card my-3">
        <div class="card-body">
            <h4 class="card-title h6">会社に当てはまる条件をすべて選択してください</h4>
            {{ Form::open(['route' => ['companies.search.exec', $company->id], 'method'=>'patch', 'id'=>'company_form', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data']) }}
                <div class="form-group">
                    @foreach($companyfacilities as $companyfacility)
                        @php
                            $isCheck = false;
                            $companyCompanyfacilities = setFormValue("companyfacilities", $company);
                            if (!empty($companyCompanyfacilities)) {
                                foreach ($companyCompanyfacilities as $companyCompanyfacility) {
                                    if ($companyfacility->id == $companyCompanyfacility->id) {
                                        $isCheck = true;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" name="companyfacilities[]" value="{{ $companyfacility->id }}" id="customCheck{{ $companyfacility->id }}" @if($isCheck) checked @endif>
                            <label class="custom-control-label" for="customCheck{{ $companyfacility->id }}">{{ $companyfacility->name }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="form-group text-center">
                    <div class="col-md-12">
                        <button class="btn btn-success">保存する</button>
                    </div>
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