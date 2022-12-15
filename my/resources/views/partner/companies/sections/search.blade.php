<div class="card card-body text-white bg-secondary mb-3">
    <form id="demo-form2" data-parsley-validate class="form-horizontal">
        <h4 class="card-title">検索</h4>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                <label class="control-label" for="search_name">会社名</label>
                <input type="text" id="search_name" name="search_name" class="form-control" style="width: 100%;" value="{{$search['search_name']}}">
            </div>

            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                <label class="control-label" for="search_status">ステータス</label>
                <select id="search_status" name="search_status" style="width: 100%;" class="select2 form-control" autocomplete="off">
                    <option value></option>
                    @foreach ($statuses as $status => $name)
                        <option @if($search['search_status'] == $status) selected="selected" @endif value="{{ $status }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                <label class="control-label" for="search_authorize_status">承認状況</label>
                <select id="search_authorize_status" style="width: 100%;" name="search_authorize_status" class="select2 form-control" autocomplete="off">
                    <option value></option>
                    @foreach ($authorize_statuses as $authorize_status => $name)
                        @if($authorize_status != App\Models\CompanyChangeRequest::AUTHORIZE_STATUS_AUTHORIZE)
                            <option @if($search['search_authorize_status'] == $authorize_status) selected="selected" @endif value="{{ $authorize_status }}">{{ $name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                <label class="control-label" for="search_request_type">申請理由</label>
                <select id="search_request_type" style="width: 100%;" name="search_request_type" class="select2 form-control" autocomplete="off">
                    <option value></option>
                    @foreach ($request_types as $request_type => $name)
                        <option @if($search['search_request_type'] == $request_type) selected="selected" @endif value="{{ $request_type }}">{{ $name }}</option>
                    @endforeach
                    </select>
            </div>
            <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12 text-right my-3 my-sm-3 my-md-3 my-lg-3 my-xl-3">
                <button type="submit" class="btn btn-success">検索</button>
            </div>
        </div>
    </form>
</div>
