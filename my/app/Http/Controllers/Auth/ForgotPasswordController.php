<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    /**
     * 以下のメソッドは、ResetsPasswordsトレイトに含みます。
     * route:password.request   method:showLinkRequestForm
     *
     * @return void
     */
    use SendsPasswordResetEmails;

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
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $credentials = $request->only('email');
        $credentials["status"] = User::STATUS_TYPE_MEMBER;
        $response = $this->broker()->sendResetLink(
            $credentials
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse(User::CONVERT_PASSWORD_RESPONSE_MESSAGES[$response] ?? $response)
                    : $this->sendResetLinkFailedResponse($request, User::CONVERT_PASSWORD_RESPONSE_MESSAGES[$response] ?? $response);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse($response)
    {
        return redirect()->back()->with(['status' => true, 'message' => trans($response)]);
    }

}
