@extends('partner.layouts.partner')

@section('title', 'スタイル管理')

@section('breadcrumbs')
{!!  Breadcrumbs::render('style')  !!}
@endsection

@section('content')
<div class="container">
    <h2 class="h3">スタイル管理</h2>

    <div class="table-responsive">
        <form method="POST" action="/style/delete">
        {{ csrf_field() }}
        <p class="pull-right">{{ link_to('style/edit', '追加・編集', ['class' => 'btn btn-primary']) }}</p>
        <table class="table table-bordered">
            <thead class="thead-light">
            <tr>
                <th>表示順</th>
                <th class="w-50">名前</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody class="table-hover">
            @foreach($list as $style)
                <tr @if($style->deleted_at)class="table-primary"@endif>
                    <td>{{ $style->order }}</td>
                    <td>{{ $style->name() }}</td>
                    <td>
                        @if ($style->deleted_at)
                        <button onclick="return confirm('表示にしますか？');" class="btn btn-success" type="submit" name="show" value="{{ $style->id }}">表示</button>
                        @else
                        <button onclick="return confirm('非表示にしますか？');" class="btn btn-danger" type="submit" name="delete" value="{{ $style->id }}">非表示</button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </form>
    </div>
</div>
@endsection
