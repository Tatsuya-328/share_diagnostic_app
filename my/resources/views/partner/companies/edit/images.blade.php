@extends('partner.layouts.partner')

@section('title', sprintf("%s画像編集", $company->name))

@section('breadcrumbs')
    {!!  Breadcrumbs::render('companies.images', $company)  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">会社登録情報 <small class="text-muted">画像設定</small></h2>
    @if ($company->status == 'publish')
        <div class="text-danger">画像の更新には審査が必要となります</div>
    @else
        <div class="text-danger">画像を登録すると申請ができるようになります</div>
    @endif
    <div class="card my-3">
        <div class="card-body">
            <div id="imageFormBox">
                    @php
                        $images = $company->currentImages();
                        $imageCount = count($images);
                    @endphp
                    @if($imageCount)
                        @foreach($images as $key => $image)
                            {{-- トップ画は除く --}}
                            <div class="card my-1">
                                @if (!empty($image) && !empty($image->filepath))
                                    <img src="{{getCdnImagePath($image->filepath)}}" class="card-img-top" />
                                @endif
                                <div class="card-body">
                                    <h5 class="imageTitle">画像{{ $key + 1 }}
                                        @if ($key == 0)
                                            <small class="text-muted">トップ画像</small>
                                        @endif
                                    </h5>
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <legend class="h6">画像説明</legend>
                                            {{ $image->alt }}
                                        </li>
                                    </ul>
                                    {{ Form::open(['route' => ['companies.image.delete', $company->id, $image->id], 'method' => 'delete', 'id'=>'company_form', 'class'=>'form-horizontal form-label-left', 'enctype'=>'multipart/form-data', 'onsubmit'=>'if (confirm("この画像を削除します。よろしいですか？\n※会社が公開中の場合は、審査が通るまで、画像は残ります")) { return true; } else { return false; }']) }}
                                    <div class="text-right my-3">
                                        <a class="btn btn-primary" href="{{ route('companies.image.edit', [$company->id, $image->id]) }}">編集</a>
                                        <button type="submit" class="btn btn-danger" >削除</button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @can("addImage", $company)
                        <div class="card my-1">
                            <div class="card-body">
                                <h5 class="imageTitle">画像{{ $imageCount + 1 }}</h5>
                                <div class="text-center">
                                    <a href="{{ route('companies.image', [$company->id]) }}"><img src="/images/imgselect_bg.png"></a>
                                </div>
                                <small class="form-text text-muted">上記の＋をクリックし画像を追加してください</small>
                            </div>
                        </div>
                    @endcan
                    @if(count($company->currentImages()))
                        <div class="text-left m-2"><a class="btn btn-secondary" href="{{ route('companies.images.order', [$company->id]) }}">画像並び替え編集</a></div>
                    @endif
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
@endsection