@extends('partner.users.sections.search')

@section('search_content')
    <div class="col-lg-3 col-md-6 col-12">
        <label class="control-label" for="name">ユーザ名</label>
        <input type="text" id="name" name="name" class="form-control" value="{{$param['name']}}">
    </div>

    <div class="col-lg-4 col-md-6 col-12">
        <label class="control-label" for="email">メールアドレス</label>
        <input type="text" id="email" name="email" class="form-control" value="{{$param['email']}}">
    </div>
    <div class="col-lg-3 col-md-6 col-12">
        <label class="control-label" for="status">ステータス</label>
        {{ Form::select('status', $status_labels, $param['status'], ['class' => 'select2 form-control', 'autocomplete' => 'off']) }}

    </div>
@endsection

