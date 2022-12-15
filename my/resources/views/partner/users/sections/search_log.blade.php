@extends('partner.users.sections.search')

@section('search_content')
    <div class="search_column col-lg-4 col-md-6 col-12">
        <label class="control-label" for="search_user_id">ユーザID</label>
        <input type="text" id="search_user_id" name="search_user_id" class="form-control" value="{{$search['search_user_id']}}">
    </div>

    <div class="search_column col-lg-4 col-md-6 col-12">
        <label class="control-label" for="search_name">ユーザ名</label>
        <input type="text" id="search_name" name="search_name" class="form-control" value="{{$search['search_name']}}">
    </div>

    <div class="search_column col-lg-4 col-md-6 col-12">
        <label class="control-label" for="search_user_id">会社ID</label>
        <input type="text" id="search_company_id" name="search_company_id" class="form-control" value="{{$search['search_company_id']}}">
    </div>

    <div class="search_column col-lg-4 col-md-6 col-12">
        <label class="control-label" for="search_name">アクション</label>
        <select id="search_action" name="search_action" class="form-control select2" autocomplete="off">
        <option value=""></option>
        @foreach ($searchActions as $key => $action)
            <option
            @if (!empty($search['search_action']))
                @if ($search['search_action'] == $key)
                    selected="selected"
                @endif
            @endif
            value="{{ $key }}">
            {{ $action }}
            </option>
        @endforeach
        </select>
    </div>

    <div class="search_column col-lg-4 col-md-6 col-12">
        <label class="control-label" for="search_log">データ文字列絞り込み</label>
        <input type="text" id="search_log" name="search_log" class="form-control" value="{{$search['search_log']}}">
    </div>
@endsection
