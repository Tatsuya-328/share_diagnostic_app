@extends('auth.layouts.auth')
@section('title', "心理テストオンライン会社機能登録")
@section('content')

{{-- mdより下 --}}
<div class="covor d-block d-lg-none">
    <img src="/images/cover_img.png" class="img-fluid" alt="心理テストオンラインカバー画像">
</div>

{{-- md以上 --}}
<div class="lg-covor d-none d-lg-block">
    <img src="/images/cover_img.png" alt="心理テストオンラインカバー画像">
</div>


<div class="container">
    <div class="jumbotron mt-3">
        <h2 class="h3">会社管理画面とは</h2>
        <hr class="my-4">
        <p>心理テストオンライン（<a href="https://shinrihoge.jp">https://shinrihoge.jp</a>）の会社検索機能に会社情報を掲載するための管理画面です。</p>
        <p>会員登録を行うことで会社を登録することができます。こちらの機能は基本無料で利用することができます。</p>
        <p class="text-warning">心理テスト会社関係者以外の方は登録しないでください。</p>
        <p class="text-warning">大変申し訳ございませんが、海外の会社様のお申し込みは受け付けておりません。ご了承ください。</p>
    </div>
    <div class="card mb-3">
        <h2 class="card-header h5">お申し込みから会社登録完了までの流れ</h2>
        <div class="card-body">
            <h3 class="card-title h6">1.会員登録</h3>
            <p class="card-text">まずは会員登録をします。以下よりご担当者様のメールアドレスを入力しユーザーを登録しましょう。</p>
            <h3 class="card-title h6">2．会社登録</h3>
            <p class="card-text">会員登録後、会社を登録しましょう。必要な情報に沿って入力し、申請ボタンを押してください。</p>
            <h3 class="card-title h6">3．掲載</h3>
            <p class="card-text">弊社独自の審査後、サイトに掲載されます。</p>
        </div>
    </div>

    <h3>利用規約</h3>
    <div class="form-group">
        <div class="row">
            <textarea class="form-control bg-white" rows=15 id="rules" style="resize: none;" readonly>
                @include('partner.staticPages.sections.rule_template', ['strips' => true])
            </textarea>
        </div>
    </div>
    <div class="form-group{{ $errors->has('pa   ssword') ? ' has-error' : '' }}" id="agreementGroup">
        <div class="text-center">
            {{ Form::open(['route' =>'agreement', 'method' => 'post']) }}
                <div class="checkbox"><label><input type="checkbox">利用規約に同意</checkbox></label></div>
                <button disabled type="submit" class="btn btn-primary mt-1">登録手続きを開始する</button></div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script src="{{ mix('assets/auth/js/login/rule.js') }}"></script>
@endsection