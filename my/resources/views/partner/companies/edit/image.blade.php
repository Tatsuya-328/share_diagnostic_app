@extends('partner.layouts.partner')
@section('title', sprintf('%sの画像%s編集', $company->name, ($order+1)))

@section('breadcrumbs')
    {!!  Breadcrumbs::render('companies.image', $company, $order)  !!}
@endsection
@section('content')
@php
    $imageCount = count($company->convertedImages());
@endphp

<div class="container">
    <h2 class="h3">会社登録情報 <small class="text-muted">画像設定</small></h2>
    <div class="card my-3">
        <div class="card-body">
            <h5 class="card-title">画像{{ $order + 1 }}</h5>
            @if(!empty($image))
                {{ Form::open(['route' => ['companies.images.update.exec', $company->id, $image->id], 'method'=>'patch', 'id'=>'company_form', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data']) }}
            @else
                {{ Form::open(['route' => ['companies.image.exec', $company->id], 'method'=>'post', 'id'=>'company_form', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data']) }}
            @endif
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="image_alt">画像<span class="required">*</span></label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input type="file" name="image_filename" class="form-control imageFileName @if($errors->has('image_filename')) parsley-error @endif" size="30" id="image_filename">
                        <small id="imageHelp" class="form-text text-muted">横800px縦600pxより小さい画像はエラーになります。<br>画像は4:3に切り取られますのでなるべく横長画像を設定してください。</small>
                        @if($errors->has('image_filename'))
                            @foreach($errors->get('image_filename') as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <label class="control-label" for="image_alt">画像説明<span class="required">*</span></label>
                    </div>
                    <div class="col-12 col-lg-8">
                        <input id="image_alt" placeholder="画像の説明を入力してください" type="text" class="form-control imageAlt @if($errors->has('image_alt')) parsley-error @endif" name="image_alt" aria-describedby="altHelp" value="@if (!empty($image)){{setFormValue('alt', $image)}}@endif" maxlength='100' required>
                        <small id="altHelp" class="form-text text-muted">最大100文字以内</small>
                        @if($errors->has('image_alt'))
                            @foreach($errors->get('image_alt') as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">保存する</button>
                </div>
            </div>
        {{ Form::close() }}
    </div>
</div>

@endsection