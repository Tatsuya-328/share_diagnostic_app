@extends('partner.layouts.partner')
@section('title', sprintf("%s詳細1編集", $company->name))

@section('breadcrumbs')
    {!!  Breadcrumbs::render('companies.detail1', $company)  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">会社登録情報 <small class="text-muted">詳細情報設定</small></h2>
    <div class="card my-3">
        <div class="card-body">
            @if (!empty($company))
                {{ Form::open(['route' => ['companies.detail1.exec', $company->id], 'method'=>'patch', 'id'=>'company_form', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data']) }}
            @endif
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="business_hours">営業時間</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <textarea type="text" aria-describedby="business_hoursHelp" class="form-control @if($errors->has('business_hours')) parsley-error @endif" id="business_hours" name="business_hours" placeholder="営業時間はなるべく細かく入れてください。改行も可能です。" >{{ setFormValue('business_hours', $company) }}</textarea>
                        <small id="business_hoursHelp" class="form-text text-muted">(例)平日 10：00～20：30<br>土日祝 10：00～17：00</small>
                        <div class="col-md-12 col-xs-12">
                            @if ($errors->has('business_hours'))
                                <ul class="parsley-errors-list filled">
                                    @foreach($errors->get('business_hours') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="holiday">定休日</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input type="text" aria-describedby="holidayHelp" class="form-control @if($errors->has('holiday')) parsley-error @endif" id="holiday" name="holiday" placeholder="定休日がない場合は「なし」と入力してください" value="{{ setFormValue('holiday', $company) }}" />
                        <small id="holidayHelp" class="form-text text-muted">(例)土日祝日</small>
                        <div class="col-md-12 col-xs-12">
                            @if ($errors->has('holiday'))
                                <ul class="parsley-errors-list filled">
                                    @foreach($errors->get('holiday') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="admission_fee">入会金</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input type="text" aria-describedby="admission_feeHelp" class="form-control @if($errors->has('admission_fee')) parsley-error @endif" id="admission_fee" name="admission_fee" placeholder="キャンペーン中の場合はその趣旨も入力下さい" value="{{ setFormValue('admission_fee', $company) }}" />
                        <small id="admission_feeHelp" class="form-text text-muted">(例)3000円</small>
                        <div class="col-md-12 col-xs-12">
                            @if ($errors->has('admission_fee'))
                                <ul class="parsley-errors-list filled">
                                    @foreach($errors->get('admission_fee') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="base_fee">基本料金</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <textarea type="text" aria-describedby="base_feeHelp" class="form-control @if($errors->has('base_fee')) parsley-error @endif" id="base_fee" name="base_fee" placeholder="月賦、チケット等の細かい情報を入力下さい" >{{ setFormValue('base_fee', $company) }}</textarea>
                        <small id="base_feeHelp" class="form-text text-muted">(例)チケット（3回券） 4,500円（税抜）※初回限定、15日間有効</small>
                        <div class="col-md-12 col-xs-12">
                            @if ($errors->has('base_fee'))
                                <ul class="parsley-errors-list filled">
                                    @foreach($errors->get('base_fee') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="rental_description">レンタル情報</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <textarea type="text" aria-describedby="rental_descriptionHelp" class="form-control @if($errors->has('rental_description')) parsley-error @endif" id="rental_description" name="rental_description" placeholder="" >{{ setFormValue('rental_description', $company) }}</textarea>
                        <small id="rental_descriptionHelp" class="form-text text-muted">(例)ハンドタオル 100円<br>心理テストマット 100円</small>
                        <div class="col-md-12 col-xs-12">
                            @if ($errors->has('rental_description'))
                                <ul class="parsley-errors-list filled">
                                    @foreach($errors->get('rental_description') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="trial_description">体験レッスン説明</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <textarea type="text" aria-describedby="trial_descriptionHelp" class="form-control @if($errors->has('trial_description')) parsley-error @endif" id="trial_description" name="trial_description" placeholder="" >{{ setFormValue('trial_description', $company) }}</textarea>
                        <small id="trial_descriptionHelp" class="form-text text-muted">(例)有料 1,000円(初回1回のみ）</small>
                        <div class="col-md-12 col-xs-12">
                            @if ($errors->has('trial_description'))
                                <ul class="parsley-errors-list filled">
                                    @foreach($errors->get('trial_description') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="company_name">運営会社名</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input type="text" class="form-control @if($errors->has('company_name')) parsley-error @endif" id="company_name" name="company_name" placeholder="(例)〇〇 " value="{{ setFormValue('company_name', $company) }}" aria-describedby="companyNameHelp" />
                        <small id="companyNameHelp" class="form-text text-muted">※ 255文字以内</small>
                        <div class="col-md-12 col-xs-12">
                            @if ($errors->has('company_name'))
                                <ul class="parsley-errors-list filled">
                                    @foreach($errors->get('company_name') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="url">ホームページ</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input type="url" aria-describedby="urlHelp" class="form-control @if($errors->has('url')) parsley-error @endif" id="url" name="url" placeholder="(例)https://shinrihoge.jp" value="{{ setFormValue('url', $company) }}" />
                        <small id="urlHelp" class="form-text text-muted">(例)https://shinrihoge.jp</small>
                        <div class="col-md-12 col-xs-12">
                            @if ($errors->has('url'))
                                <ul class="parsley-errors-list filled">
                                    @foreach($errors->get('url') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="facebook">Facebookアカウント</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="facebookText">@</span>
                            </div>
                            <input type="text" aria-describedby="facebookTextHelp" class="form-control @if($errors->has('facebook')) parsley-error @endif" id="facebook" name="facebook" aria-describedby="facebookText" placeholder="shinrihogeonline"  value="{{ setFormValue('facebook', $company) }}" autocapitalize="off">
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <small id="facebookTextHelp" class="form-text text-muted">(例)@shinrihogeonline</small>
                            @if ($errors->has('facebook'))
                                <ul class="parsley-errors-list filled">
                                    @foreach($errors->get('facebook') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="instagram">Instagramアカウント</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="instagramText">@</span>
                            </div>
                            <input type="text" aria-describedby="instagramTextHelp" class="form-control @if($errors->has('instagram')) parsley-error @endif" id="instagram" name="instagram" aria-describedby="instagramText" placeholder="shinrihogeonline"  value="{{ setFormValue('instagram', $company) }}" autocapitalize="off">
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <small id="instagramTextHelp" class="form-text text-muted">(例)shinrihogeonline</small>
                            @if ($errors->has('instagram'))
                                <ul class="parsley-errors-list filled">
                                    @foreach($errors->get('instagram') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="twitter">Twitterアカウント</label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="twitterText">@</span>
                            </div>
                            <input type="text" aria-describedby="twitterTextHelp" class="form-control @if($errors->has('twitter')) parsley-error @endif" id="twitter" name="twitter" aria-describedby="twitterText" placeholder="shinrihogejp"  value="{{ setFormValue('twitter', $company) }}" autocapitalize="off">
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <small id="twitterTextHelp" class="form-text text-muted">(例)shinrihogejp</small>
                            @if ($errors->has('twitter'))
                                <ul class="parsley-errors-list filled">
                                    @foreach($errors->get('twitter') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
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

@section('styles')
    @parent
@endsection

@section('scripts')
    @parent
@endsection