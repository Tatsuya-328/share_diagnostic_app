<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use App\Models\User;
use Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    /**
     * 以下のメソッドは、ResetsPasswordsトレイトに含みます。
     * route:password.reset   method:showResetForm
     *
     * @return void
     */
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|string|email|max:100|banned_email',
            'password' => 'required|string|regex:/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]+\z/i|min:8|max:20|confirmed',
        ];
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $validater = Validator::make($request->all(), $this->rules());
        if ($validater->fails()) {
            return redirect()->back()
                ->with(['status' => false, 'message' => 'データの更新に失敗しました。入力値を確認して下さい'])
                ->withErrors($validater->errors())
                ->withInput();
        }

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse(User::CONVERT_PASSWORD_RESPONSE_MESSAGES[$response])
                    : $this->sendResetFailedResponse($request, User::CONVERT_PASSWORD_RESPONSE_MESSAGES[$response]);
    }


}
