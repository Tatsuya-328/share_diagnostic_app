@extends('partner.layouts.partner')
@section('title', "ヘルプ")
@section('content')
<div class="container mt-4">
    <h2 class="h3">よくある質問</h2>
    <div class="row">
        <div class="col-12">
            <h3 class="h5">会社を掲載するまでの流れを教えてください。</h3>
            <p>会員登録が完了すると、会社情報を登録することができるようになります。必要な情報を記入し、基本情報ページの下部にある「新規登録申請」を押してください。申請後、弊社にて確認を行い、承認されると会社情報が公開されます。確認は、５営業日以内に行います。</p>
            <h3 class="h5">掲載は有料ですか？</h3>
            <p>無料となっております。</p>
            <h3 class="h5">会員登録情報を変更・更新することはできますか？</h3>
            <p>会員登録時に設定したメールアドレス、パスワード、ニックネームは、「会員情報変更」より、いつでもご変更いただけます。</p>
            <h3 class="h5">会社の登録情報を変更・更新することはできますか？</h3>
            <p>会社の基本情報の変更には、審査（５営業日以内）が必要です。変更箇所を入力して保存し、基本情報ページの下部にある「変更申請」を押してください。詳細情報、アクセス、スタイル／条件、編集メンバーは、いつでも変更することが可能です。</p>
            <h3 class="h5">写真はアップロードが必要ですか？</h3>
            <p>会社の掲載には、1枚以上のアップロードが必要です。会社の内観、レッスン中の様子など、複数の写真をアップロードすると、雰囲気が伝わりやすいです。</p>
            <h3 class="h5">会社の登録情報を削除することはできますか？</h3>
            <p>会社の基本情報の削除には、審査（５営業日以内）が必要です。基本情報ページの下部にある「削除申請」を押してください。</p>
            <h3 class="h5">メールが届かないときには</h3>
            <p>メールアドレス認証や変更、パスワードの変更などの際にメールが受信できないときには以下をご確認ください。</p>
            <ol>
                <li>shinrihoge.jpからのメールが受け取れるよう設定する。</li>
                <li>迷惑メールフォルダに届いていないか確認する</li>
                <li>入力したメールアドレスに間違いがないか確認する</li>
                <li>別のメールアドレスを利用する</li>
            </ol>
            <h3 class="h5">上記のよくある質問で解決しない場合</h3>
            <p>大変お手数ではございますが、下記フォームからご連絡ください。</p>
            <p>お問い合わせは<a href="https://docs.google.com/forms/d/e/1FAIpQLSeoEHkNJgUcpR4Th41x5S482XkFX48TRkCk6e0z4k-8pKQITg/viewform?usp=pp_url&entry.324308208={{ Request::url()}}&entry.1229071582" target="_blank" itemprop="url">こちら</a></p>
        </div>
    </div>
</div>
@endsection