<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('rule', function() {
    return view('staticPages.rule');
})->name('rule');
Route::get('privacy', function() {
    return view('staticPages.privacy');
})->name('privacy');
Route::get('help', function() {
    return view('staticPages.help');
})->name('help');
Route::get('bye', function() {
    return view('auth.bye'); //退会後
})->name('bye');

// ログイン時（partner）
Route::group(["namespace" => "Partner", 'middleware' => 'auth'], function() {
    Route::get('/', "HomeController@index")->name('home');
    Route::get('user/edit', 'UserController@edit')->name('user.edit');
    Route::post('user/edit', 'UserController@store')->name('user.namepass.store');
    Route::get('user/email', 'UserController@email')->name('user.email');
    Route::post('user/email', 'UserController@changeEmail')->name('user.email.change');
    Route::get('user/email/change', 'UserController@sentTokenMail')->name('user.email.sent');
    Route::get('user/leave', "UserController@leave")->name('leave');
    Route::post('user/leave', "UserController@leaveExec")->name('leave.exec');
    Route::get('companies', 'CompanyController@index')->name('companies');
    Route::get('companies/create', 'CompanyController@create')->name('companies.create');
    Route::post('companies/update_base', 'CompanyController@updateBase')->name('companies.create.exec');
    // 会社削除承認
    // Route::post('companies/judge/delete/{company}', 'CompanyController@judgeDelete')->name('companies.judge.delete');

    // 会社メンバー以上
    Route::group(['middleware' => ['memberonly']], function() {
         Route::get('companies/{company}', 'CompanyController@info')->name('companies.info')->where('company', '[0-9]+');
    });

    // 会社Owner以上
    Route::group(['middleware' => ['owneronly']], function() {
        Route::get('companies/{company}/users', 'CompanyController@users')->name('companies.users');
        Route::post('companies/{company}/users/remove', 'CompanyController@removeUser')->name('companies.users.remove');
        Route::post('companies/{company}/users/invite', 'CompanyController@inviteUser')->name('companies.users.invite');
        // 会社詳細情報1
        Route::get('companies/{company}/detail1', 'CompanyController@detail1')->name('companies.detail1');
        Route::patch('companies/{company}/detail1', 'CompanyController@updateDetail1')->name('companies.detail1.exec');
        // 会社詳細情報2
        Route::get('companies/{company}/detail2', 'CompanyController@detail2')->name('companies.detail2');
        Route::patch('companies/{company}/detail2', 'CompanyController@updateDetail2')->name('companies.detail2.exec');
        // 会社検索条件
        Route::get('companies/{company}/search', 'CompanyController@search')->name('companies.search');
        Route::patch('companies/{company}/search', 'CompanyController@updateSearch')->name('companies.search.exec');
        // 会社画像設定
        Route::get('companies/{company}/images', 'CompanyController@images')->name('companies.images');
        Route::get('companies/{company}/image', 'CompanyController@image')->name('companies.image');
        Route::get('companies/{company}/image/{image}', 'CompanyController@editImage')->where('image', '[0-9]+')->name('companies.image.edit');
        Route::post('companies/{company}/image', 'CompanyController@updateImage')->name('companies.image.exec');
        Route::patch('companies/{company}/image/{image}', 'CompanyController@updateImage')->where('order', '[0-9]+')->name('companies.images.update.exec');
        Route::delete('companies/{company}/image/{image}', 'CompanyController@destroyImage')->where('image', '[0-9]+')->name('companies.image.delete');
        Route::patch('companies/{company}/images', 'CompanyController@updateImages')->name('companies.images.exec');

        // 画像並び替え
        Route::get('companies/{company}/images/order', 'CompanyController@imagesOrder')->name('companies.images.order');
        Route::patch('companies/{company}/images/order', 'CompanyController@updateimagesOrder')->name('companies.images.order.exec');
        // 会社新規登録申請（ユーザー用）
        Route::post('companies/apply/registration/{company}', 'CompanyController@applyRegistration')->name('companies.apply.registration');
        // 会社重要項目変更申請（ユーザー用）
        Route::post('companies/apply/update/{company}', 'CompanyController@applyUpdate')->name('companies.apply.update');
        // 会社削除申請（ユーザー用）
        Route::post('companies/apply/delete/{company}', 'CompanyController@applyDelete')->name('companies.apply.delete');
        // 会社申請取り消し（ユーザー用）
        Route::post('companies/apply/cancel/{company}', 'CompanyController@applyCancel')->name('companies.apply.cancel');
         // 会社基本情報
        Route::get('companies/{company}/base', 'CompanyController@base')->name('companies.base');
        Route::patch('companies/{company}/base', 'CompanyController@updateBase')->name('companies.base.exec');
    });

    // 管理者（admin）
    Route::group(['middleware' => ['adminonly']], function() {
        Route::get('user', 'UserController@list')->name('user.list');
        Route::get('user/{id}', 'UserController@detail')->where('id', '[0-9]+')->name('user.detail');
        Route::post('user/{id}', 'UserController@storeMemo')->where('id', '[0-9]+')->name('user.memo.store');
        Route::get('user/log', 'UserController@log')->name('user.log');
        // 会社審査開始
        Route::post('companies/judge/processing/{company}', 'CompanyController@judgeProcessing')->name('companies.judge.processing');
        // 会社承認
        Route::post('companies/judge/authorize/{company}', 'CompanyController@judgeAuthorize')->name('companies.judge.authorize');
        // 会社却下（管理者用）
        Route::post('companies/judge/reject/{company}', 'CompanyController@judgeReject')->name('companies.judge.reject');
    });
});

//会員登録周り（未ログイン）
Route::group(['namespace' => 'Auth'], function () {
    // Authentication Routes...
    Route::get('register', 'RegisterController@showRegistrationForm')->name("register");
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login')->name('login.exec');
    Route::get('join', 'LoginController@join')->name('join');
    Route::post('agreement', 'RegisterController@agreement')->name('agreement');
    Route::get('logout', 'LoginController@logout')->name('logout');
    Route::post('provisional', 'RegisterController@provisional')->name('provisional');
    Route::get('provisional/info/{user}', 'RegisterController@provisionalInfo')->name('provisional.info');
    Route::get('registered/success', 'RegisterController@success')->name('register.success');
    Route::get('registered/{token}', 'RegisterController@verified')->name('verified');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset')->name('password.reset.exec');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/request', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::get('user/email/auth/{token}', 'UserController@AuthEmail')->name('user.email.auth');
    Route::get('user/email/success', 'UserController@success')->name('user.email.success');
});

