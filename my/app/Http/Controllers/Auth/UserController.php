<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLog;
use Auth;

class UserController extends Controller
{
    /**
     * route user/email/auth/{token} (GET)
     */
    public function authEmail(Request $request, $token)
    {
        $user = User::where('email_verify_token', $token)->first();
        if (empty($user) || $user->change_email_requested_at < date_create("-" . config('const.userRegisterExpireHour') . " hour")) {
            return view('partner.users.email_auth', ['eMessage' => "認証URLの有効期限が切れています。再度メールアドレス変更を行ってください。"]);

        } elseif (User::where('email', $user->change_email)->where('status', '=', User::STATUS_TYPE_MEMBER)->count() > 0) {
            return view('partner.users.email_auth', ['eMessage' => "このメールアドレスはすでに登録されています。"]);
        }

        $user->email = $user->change_email;
        $user->change_email = null;
        $user->email_verify_token = null;
        $user->save();
        UserLog::store($request);

        Auth::login($user); //このユーザでログイン

        return redirect()->route('user.email.success');
    }

    public function success()
    {
        return view('partner.users.email_auth');
    }
}

