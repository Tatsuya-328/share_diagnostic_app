@extends('partner.layouts.partner')

@section('title', sprintf("%sアクセス編集", $company->name))

@section('breadcrumbs')
    {!!  Breadcrumbs::render('companies.detail2', $company)  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">会社登録情報 <small class="text-muted">アクセス設定</small></h2>
    <div class="card my-3">
        @php
            $companyStations = setFormValue('stations', $company);
            // バリデーションエラーの時
            $list = [];
            if (is_array($companyStations) && !empty($errors)) {
                foreach ($companyStations as $companyStation) {
                    if (is_string($companyStation)) {
                        $station = App\Models\Station::with("railroad:id,name")->where("id", $companyStation)->first();
                        $list[] = $station;
                    }
                }
                $companyStations = $list;
            }
        @endphp
        <input type="hidden" id="railroads" data-list="{{ json_encode($railroads) }}">
        <input type="hidden" id="companyStations" data-list="{{ json_encode($companyStations) }}">
        <div id="CompanyStationsVue">
            @include('partner.companies.edit.sections.stations')
            <div class="card-body">
                {{ Form::open(['route' => ['companies.detail2.exec', $company->id], 'method' => 'patch', 'id'=>'company_form', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data', 'submit.prevent'=>'exec']) }}
                    <div class="form-group">
                        <div class="row" v-if="showModal == ''">
                            <div class="col-12 col-lg-4">
                                <label class="control-label" for="name">最寄駅</label>
                            </div>
                            <div v-cloak class="col-12 col-lg-8">
                                <ul class="list-group mb-3">
                                <li is="CompanyStations"
                                    v-for="(companyStation, index) in companyStations"
                                    :id="companyStation.id"
                                    :group_code="companyStation.group_code"
                                    :name='companyStation.name'
                                    :railroad='companyStation.railroad'
                                    @remove='remove(index, companyStation)'
                                ></li>
                                </ul>
                                <button class="btn btn-primary" type="button" aria-describedby="stationSetting" :disabled="!enabled" @click="showstationSettingModal">最寄駅を設定</button>
                                <small id="stationSetting" class="form-text text-muted">3駅まで設定可能です</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" v-show="!showModal">
                        <div class="row">
                            <div class="col-12 col-lg-4">
                                <label class="control-label" for="address_description">アクセス</label>
                            </div>
                            <div class="col-12 col-lg-8">
                                <textarea rows="8" name="address_description" placeholder="（例）茨城県庁から車で4分、水戸駅から車で15分
                                茨城東ICから車で5分" class="form-control @if($errors->has('address_description')) parsley-error @endif">{{ setFormValue('address_description', $company) }}</textarea>
                                <div class="col-md-12 col-xs-12">
                                    @if ($errors->has('address_description'))
                                        <ul class="parsley-errors-list filled">
                                            @foreach($errors->get('address_description') as $error)
                                                <li class="parsley-required">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" v-if="!showModal">
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
</div>
@endsection

@section('styles')
    @parent
@endsection

@section('scripts')
    @parent
    <script src="{{ mix('assets/partner/js/companies/edit_detail2.js') }}"></script>
@endsection
