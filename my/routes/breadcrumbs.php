<?php

// Home
Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});

// Home > 会社一覧
Breadcrumbs::register('companies', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('会社一覧', route('companies'));
});

Breadcrumbs::register('companies.create', function ($breadcrumbs) {
    $breadcrumbs->parent('companies');
    $breadcrumbs->push("新規登録", route('companies.create'));
});

Breadcrumbs::register('companies.info', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('companies');
    $breadcrumbs->push($company->name, route('companies.info', $company->id));
});

Breadcrumbs::register('companies.base', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('companies.info', $company);
    $breadcrumbs->push("基本情報設定", route('companies.base', $company->id));
});

Breadcrumbs::register('companies.images', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('companies.info', $company);
    $breadcrumbs->push("画像一覧", route('companies.images', $company->id));
});

Breadcrumbs::register('companies.image', function ($breadcrumbs, $company, $order) {
    $breadcrumbs->parent('companies.images', $company);
    $breadcrumbs->push("画像" . ($order+1) ."設定", route('companies.image', $company->id));
});

Breadcrumbs::register('companies.images_order', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('companies.info', $company);
    $breadcrumbs->push("画像並び替え", route('companies.images.order', $company->id));
});

Breadcrumbs::register('companies.detail1', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('companies.info', $company);
    $breadcrumbs->push("詳細情報設定", route('companies.detail1', $company->id));
});

Breadcrumbs::register('companies.detail2', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('companies.info', $company);
    $breadcrumbs->push("アクセス設定", route('companies.detail2', $company->id));
});

Breadcrumbs::register('companies.search', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('companies.info', $company);
    $breadcrumbs->push("条件設定", route('companies.search', $company->id));
});

Breadcrumbs::register('companies.users', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('companies.info', $company);
    $breadcrumbs->push("メンバー");
});

Breadcrumbs::register('user.log', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('ユーザ管理', route('user.list'));
    $breadcrumbs->push('ログ', route('user.log'));
});

Breadcrumbs::register('user.list', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('ユーザ管理', route('user.list'));
});

Breadcrumbs::register('user.detail', function ($breadcrumbs, $user) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('ユーザ管理', route('user.list'));
    $breadcrumbs->push($user->name, route('user.detail', ['id' => $user->id]));
});

Breadcrumbs::register('user.edit', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('ユーザ情報変更', route('user.edit'));
});

Breadcrumbs::register('user.email', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('メールアドレス変更', route('user.email'));
});
