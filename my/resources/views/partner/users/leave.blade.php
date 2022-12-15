@extends('partner.layouts.partner')
@section('content')
<div class="container">
    <h2 class="h3">退会</h2>
    <div class="mb-3">
        <p>退会するとデータが削除され、ログインできなくなります。</p>
        <div class="text-center">
            <form method="POST" action="/user/leave" onsubmit="return confirm('本当に退会しますか？');">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-danger">退会する</button>
            </form>
            <a href="/user/edit"><button type="submit" class="btn btn-primary mt-3">戻る</button></a>
        </div>
    </div>
</div>
@endsection
