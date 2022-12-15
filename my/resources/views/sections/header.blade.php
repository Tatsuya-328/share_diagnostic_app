<header>

    <div class="">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="navbar-brand" href="{{ route('home') }}">{{ config('const.appName') }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarmenu" aria-controls="navbarmenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarmenu">
                <ul class="navbar-nav ml-auto navbar-right">
                    @auth
                        <li class="nav-item"><a class="nav-link" href="/companies">会社</a></li>
                        <li class="nav-item"><a class="nav-link" href="/user/edit">会員情報変更</a></li>
                        @if (isAdmin())
                            <li class="nav-item"><a class="nav-link" href="/user">ユーザ<span class="badge badge-secondary">PC</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="/user/log">ユーザーログ<span class="badge badge-secondary">PC</span></a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}">ログアウト</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">ログイン</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('join') }}">会員登録</a></li>
                    @endauth
                </ul>
            </div>
        </nav>
    </div>
</header>