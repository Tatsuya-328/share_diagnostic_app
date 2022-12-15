@extends('partner.layouts.partner')
@section('title', "利用規約")
@section('content')
<div class="container mt-4">
    <h2 class="h3">利用規約</h2>
    <div class="row">
        <div class="col-12">
            @include('partner.staticPages.sections.rule_template', ['strips' => false])
        </div>
    </div>
</div>

@endsection