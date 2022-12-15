<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Const Value
    |--------------------------------------------------------------------------
    */
    // ドメインURL
    'frontDomain' => env('APP_URL', ''),
    // イメージURL
    'cdnImageUrl' => env('APP_CDN_IMAGE_URL', ''),
    // GTMのID
    'googleTagManagerId' => env('GOOGLE_TAG_MANAGER_ID', ''),
    'googleTagManagerAuth' => env('GTM_AUTH', ''),
    'googleTagManagerPreview' => env('GTM_PREVIEW', ''),
    'appLocalName' => '心理テストオンライン',
    'appName' => '心理テスト会社管理',
    'emailAdminAdress' => 'noreply@my.shinrihoge.jp',
    // todo 文言が決まり次第変更する
    'emailContactAdress' => 'noreply@shinrihoge.jp',
    'userRegisterExpireHour' => 24,
    // 利用規約に同意の有効期限(分)
    'agreementExpire' => 24 * 60,
    // ユーザー登録時のトークンの長さ
    'registerTokenLength' => 40,
    'userAdminPermission' => 200,
    'googleMapApiKey' => env('GOOGLE_MAP_API_KEY', ''),
    'basePostCodeDomain' => env('POST_CODE_JSON_DOMAIN'),
    // FusionテーブルID
    'fusionTableId' => env('FUSION_TABLE_ID'),
    // メールテンプレートの件名を記載
    'mailSubjects' => [
        'auth.emails.passwords.reset'             => '[心理テストオンライン] パスワード変更',
        'auth.emails.register.provisional'        => '[心理テストオンライン] 仮登録完了',
        'auth.emails.register.verified'           => '[心理テストオンライン] 本登録完了',
        'auth.emails.register.change_email'       => '[心理テストオンライン] メールアドレス変更',
        'partner.emails.companies.authorize.signup' => '[心理テストオンライン] 心理テスト会社新規登録につきまして',
        'partner.emails.companies.authorize.update' => '[心理テストオンライン] 心理テスト会社項目変更につきまして',
        'partner.emails.companies.authorize.delete' => '[心理テストオンライン] 心理テスト会社削除につきまして',
        'partner.emails.companies.reject.signup'    => '[心理テストオンライン] 心理テスト会社新規登録につきまして',
        'partner.emails.companies.reject.update'    => '[心理テストオンライン] 心理テスト会社項目変更につきまして',
        'partner.emails.companies.reject.delete'    => '[心理テストオンライン] 心理テスト会社削除につきまして',
        'partner.emails.companies.invite_user'      => '[心理テストオンライン] 心理テスト会社編集メンバーへの招待',
    ]
];
