{{-- 申請周り --}}
<div class="mt-5 mb-3">
    <h5>申請</h5>
    <div class="clearfix ">
    @can('registration', $company)
        @if ($company->isImageExist())
            <span class="float-left m-2">
                {{ Form::open(['route'=> ["companies.apply.registration", $company->id], 'method'=>'post', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data', 'id' => 'applyRegisterForm']) }}
                <button type="submit" class="btn btn-success">新規登録申請</button>
                {{ Form::close() }}
            </span>
        @else
            <p class="text-danger">画像が1枚以上ないと申請出来ません</p>
        @endif
    @endcan
    @can('updateApply', $company)
        @if ($company->isImageExist())
            <span class="float-left m-2">
                {{ Form::open(['route'=> ["companies.apply.update", $company->id], 'method'=>'post', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data']) }}
                    <button type="submit" class="btn btn-warning">変更申請</button>
                {{ Form::close() }}
            </span>
        @else
            <p class="text-danger">画像が1枚以上ないと申請出来ません</p>
        @endif
    @endcan
    @can('delete', $company)
        <span class="float-left m-2">
            {{ Form::open(['route'=> ["companies.apply.delete", $company->id], 'method'=>'post', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data', 'onsubmit' => 'if (confirm("この会社を削除申請に出します。よろしいですか？\n※ 削除が承認された会社は、元には戻せません。")) { return true; } else { return false; }']) }}
                <button type="submit" class="btn btn-danger">削除申請</button>
            {{ Form::close() }}
        </span>
    @endcan
    @can('cancel', $company)
        <span class="float-left m-2">
            {{ Form::open(['route'=> ["companies.apply.cancel", $company->id], 'method'=>'post', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data', 'onsubmit' => 'if (confirm("申請を取り消しますか？")) { return true; } else { return false; }']) }}
                <button type="submit" class="btn btn-outline-primary">申請取り消し</button>
            {{ Form::close() }}
        </span>
    @endcan

    @can('processing', $company)
        <span class="float-left m-2">
            {{ Form::open(['route'=> ["companies.judge.processing", $company->id], 'method'=>'post', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data']) }}
                <button type="submit" class="btn btn-warning">審査開始</button>
            {{ Form::close() }}
        </span>
    @endcan
    @can('authorize', $company)
        <span class="float-left m-2">
            {{ Form::open(['route'=> ["companies.judge.authorize", $company->id], 'method'=>'post', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data', 'onsubmit' => 'if (confirm("この会社を承認します。よろしいですか？")) { return true; } else { return false; }']) }}
                <button type="submit" class="btn btn-success">{{ $company->companyChangeRequest->request_type == "delete" ? "承認して削除" : "承認して公開" }}</button>
            {{ Form::close() }}
        </span>
    @endcan
    @can('reject', $company)
        <span class="float-left m-2">
            <button type="button" class="btn btn-secondary"  data-toggle="modal" data-target="#rejectModal">却下する</button>
        </span>
        <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">却下</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{ Form::open(['route'=> ["companies.judge.reject", $company->id], 'method'=>'post', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data' ]) }}
                    <div class="modal-body">
                        <p>却下理由を記載してください</p>
                        <textarea name="message" rows="10" id="message" placeholder="会社側に表示されます" class="form-control formModal"  required>{{ !empty(old('message')) ? old('message') : "" }}</textarea>
                        @if ($errors->has('message'))
                            @foreach($errors->get('message') as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">却下する</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    @endcan
    </div>
</div>