@extends('partner.layouts.partner')

@if (!empty($company))
    @section('title', sprintf("%s基本情報編集 | %s", $company->name, config('const.appName')))
@else
    @section('title', sprintf("会社登録 | %s", config('const.appName')))
@endif

@section('breadcrumbs')
  @if (!empty($company))
    {!!  Breadcrumbs::render('companies.base', $company)  !!}
  @else
    {!!  Breadcrumbs::render('companies.create')  !!}
  @endif
@endsection

@section('content')
<div class="container">
    <h2 class="h3">{{ !empty($company) ? '会社情報編集 ' : '会社情報登録 ' }}<small class="text-muted">基本情報設定</small></h2>
    @if (!empty($company))
        <div class="text-danger">基本情報の更新には審査が必要となります</div>
    @else
        <div class="text-danger">こちらの基本情報と画像を登録すると申請ができるようになります</div>
    @endif
    <div class="card my-3">
        <div class="card-body">

            @if (!empty($company))
                {{ Form::open(['route' => ['companies.base.exec', $company->id], 'method'=>'patch', 'id'=>'company_form', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data']) }}
            @else
                {{ Form::open(['route' => 'companies.create.exec', 'method'=>'post', 'id'=>'company_form', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data']) }}
            @endif
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="name">会社名<span class="required">* </span></label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input type="text" class="form-control @if($errors->has('name')) parsley-error @endif" id="name" name="name" placeholder="会社名を入力" aria-describedby="nameHelp" value="{{ setFormValue('name',!empty($company->companyChangeRequest) ? $company->companyChangeRequest : $company) }}" required/>
                        <small id="nameHelp" class="form-text text-muted">255文字以内で入力してください</small>
                        @if ($errors->has('business_hours'))
                            @foreach($errors->get('business_hours') as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="description">紹介文<span class="required">* </span></label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <textarea class="form-control @if($errors->has('description')) parsley-error @endif" id="description" rows="5" name="description" placeholder="会社の雰囲気や環境などがわかるように入力してください" required>{{ setFormValue('description', !empty($company->companyChangeRequest) ? $company->companyChangeRequest : $company) }}</textarea>
                        @if ($errors->has('business_hours'))
                            @foreach($errors->get('business_hours') as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <h5 class="card-title">住所</h5>
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="post_code">郵便番号<span class="required">* </span></label>
                    </div>
                    <div class="col-12 col-lg-6">
                        <input type="hidden" id="basePostCodeDomain" value="{{ config('const.basePostCodeDomain') }}">
                        <input type="text" name="post_code" id="post_code" class="form-control @if($errors->has('post_code')) parsley-error @endif" autocomplete="postal-code" placeholder="(例)1630808" aria-describedby="postcodeHelp" value="{{ setFormValue('post_code',!empty($company->companyChangeRequest) ? $company->companyChangeRequest : $company) }}" maxlength="7" required>
                        <small id="postcodeHelp" class="form-text text-muted">ハイフンなしで7桁の半角数字を入力して下さい。<br>郵便番号を入れて「住所検索」を押すと都道府県、市区町村・地域名が自動で補完されますので表示内容をご確認下さい。</small>
                        <div id="errorSearchAddress" style="display: none;">
                            <p class="text-danger">該当する住所が見つかりませんでした</p>
                        </div>
                        @if ($errors->has('post_code'))
                            @foreach($errors->get('post_code') as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-12 col-lg-2">
                        <button class="btn btn-primary" type="button" id="searchAddress">住所検索</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="prefecture_id">都道府県<span class="required">* </span></label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <select name="prefecture_id" id="prefecture_id" class="form-control @if($errors->has('prefecture_id')) parsley-error @endif" required>
                            <option value=""></option>
                            @foreach($prefectures as $prefecture)
                                <option value="{{ $prefecture->id }}" @if(setFormValue('prefecture_id', !empty($company->companyChangeRequest) ? $company->companyChangeRequest : $company) == $prefecture->id) selected @endif>{{ $prefecture->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('prefecture_id'))
                            @foreach($errors->get('prefecture_id') as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="address1">市区町村・地域名<span class="required">* </span></label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input type="text" name="address1" aria-describedby="address1Help" placeholder="(例)千代田区麹町" id="address1" class="form-control @if($errors->has('address1')) parsley-error @endif" value="{{ setFormValue('address1',!empty($company->companyChangeRequest) ? $company->companyChangeRequest : $company) }}" required>
                        <small id="address1Help" class="form-text text-muted">(例)〇〇市〇〇</small>
                        @if ($errors->has('address1'))
                            @foreach($errors->get('address1') as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                        @if ($errors->has('city_id'))
                            @foreach($errors->get('city_id') as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
                {{-- 管理者でcity_idがない場合は通知 --}}
                @if (isAdmin() && !empty($company) && $company->notHasCityId())
                    <div class="form-group">
                        <p class="text-danger">市区町村が検出できないため市区町村の検索結果に表示できません。住所の修正をお願いします。</p>
                        <a href="#" role="link" data-toggle="modal" data-target="#copyCityModal">こちら</a>から市区町村をコピーして下さい
                        <p style="display: none;" class="text-info" id="copiedNotice">コピーしました。</p>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="modal fade" id="copyCityModal" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">市区町村をコピー</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>市区町村をコピーして、「市区町村・地域名」に貼り付けてください</p>
                                                <div class="text-center">
                                                    @foreach($company->prefecture->cities as $city)
                                                        <span class="">
                                                            <input class="cityName text-center" id="city{{ $city->id }}" type="text" value="{{ $city->name }}" readonly>
                                                        </span>
                                                    @endforeach
                                                </div>
                                                <div class="text-center">
                                                    <button type="button" id="copyCityBtn" class="btn btn-primary mt-3">コピー</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="address2">番地<span class="required">* </span></label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input type="text" name="address2" aria-describedby="address2Help" placeholder="(例)1丁目2−3" id="address2" class="form-control @if($errors->has('address2')) parsley-error @endif" value="{{ setFormValue('address2',!empty($company->companyChangeRequest) ? $company->companyChangeRequest : $company) }}" required>
                        <small id="address2Help" class="form-text text-muted">(例)〇丁目〇−〇</small>
                        @if ($errors->has('address2'))
                            @foreach($errors->get('address2') as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="address_building">建物名</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input type="text" name="address_building" aria-describedby="address_building2Help" placeholder="(例)〇〇ビル" id="address_building" class="form-control @if($errors->has('address_building')) parsley-error @endif" value="{{ setFormValue('address_building',!empty($company->companyChangeRequest) ? $company->companyChangeRequest : $company) }}">
                        <small id="address_building2Help" class="form-text text-muted">(例)〇〇ビル〇階〇〇号室</small>
                        @if ($errors->has('address_building'))
                            @foreach($errors->get('address_building') as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="tel_num">電話番号<span class="required">* </span></label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input type="tel" class="form-control @if($errors->has('tel_num')) parsley-error @endif" id="tel_num" name="tel_num" placeholder="電話番号を入力" aria-describedby="telHelp" value="{{ setFormValue('tel_num',!empty($company->companyChangeRequest) ? $company->companyChangeRequest : $company) }}" required>
                        <small id="telHelp" class="form-text text-muted">ハイフンなしで半角数字のみ入力して下さい</small>
                        @if ($errors->has('tel_num'))
                            @foreach($errors->get('tel_num') as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12 col-xs-12 text-center">
                        <input type="submit" class="btn btn-success" value="保存する">
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script src="{{ mix('assets/partner/js/companies/edit_base.js') }}"></script>
@endsection
