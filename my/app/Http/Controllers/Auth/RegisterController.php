<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use DB;
use Cookie;
use Exception;
use Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * 以下のメソッドは、RegistersUsersトレイトに含みます。
     * route:register   method:showRegistrationForm
     *
     * @return void
     */
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = "/";

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if ($data['status'] == User::STATUS_TYPE_PROVISIONAL) {
            $validator = Validator::make($data, [
                'name' => 'required|string|max:50',
                'email' => [
                        'required',
                        'string',
                        'email',
                        'max:100',
                        'banned_email',
                        Rule::unique('users')->where(function($query) {
                            return $query->where("status", "=", User::STATUS_TYPE_MEMBER);
                        })
                    ],
                'password' => 'required|string|regex:/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]+\z/i|min:8|max:20|confirmed',
            ]);
        } else {
            return false;
        }
        return $validator;
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {
        $isAgreement = Cookie::get('is_agreement_rule') ?? false;
        if (!$isAgreement) {
            return redirect()->route("join");
        }
        return view('auth.register.index');
    }


    public function agreement()
    {
        Cookie::queue('is_agreement_rule', true, config('const.agreementExpire'));
        return redirect()->route('register');
    }

    /**
     * 仮登録
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function provisional(Request $request)
    {
        $request->merge(['status' => User::STATUS_TYPE_PROVISIONAL]);
        $params = $request->all();
        $this->validator($params)->validate();
        DB::beginTransaction();
        try {
            event(new Registered($user = User::create($params)));
            DB::commit();
        } catch(Exception $e) {
            Log::error($e);
            DB::rollback();
            return back()->with(['status' => false, 'message' => User::getMailErrorMessage($e)]);
        }

        return redirect()->route('provisional.info', [$user->id]);
    }

    public function provisionalInfo(Request $request, User $user)
    {
        if (empty($user) || date('Y-m-d H:i:s', strtotime($user->provisional_registered_at)) < date('Y-m-d H:i:s', strtotime("-" . config('const.userRegisterExpireHour') . " hour"))) {
            return redirect()->route('join')
                    ->with(['status' => false, 'message' => '認証URLの有効期限が切れています。再度登録処理を行ってください。']);
        } else if ($user->status != User::STATUS_TYPE_PROVISIONAL) {
            return redirect()->route('join')
                    ->with(['status' => false, 'message' => 'こちらのメールアドレスは既に登録されています。']);
        }
        return view('auth.register.provisional', ['user' => $user]);
    }

    public function success()
    {
        return view('auth.register.success');
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function verified(Request $request, $token)
    {
        // 仮登録で、トークンが一致するユーザーを取得
        $user = User::where('email_verify_token', $token)
                    ->where('status', User::STATUS_TYPE_PROVISIONAL)
                    ->first();
        if (empty($user) || date('Y-m-d H:i:s', strtotime($user->provisional_registered_at)) < date('Y-m-d H:i:s', strtotime("-" . config('const.userRegisterExpireHour') . " hour")) || !empty(User::getByEmail($user->email))) {
            return view('auth.register.verified', ['eMessage' => "認証URLの有効期限が切れています。再度登録処理を行ってください。", 'eCode' => 1]);
        }

        DB::beginTransaction();
        try {
            $admin = Admin::with(['permission'])->where('email', $user->email)->first();
            if (!empty($admin) && ($admin->permission_id <= config('const.userAdminPermission'))) {
                $adminId = $admin->id;
            }

            $params = [
                'user' => $user,
                'status' => User::STATUS_TYPE_MEMBER,
                'adminId' => $adminId ?? null,
                'email_verify_token' => null
            ];

            event(new Registered($user = User::register($params, $request)));
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            return view('auth.register.verified', ['eMessage' => "本登録に失敗しました。お手数おかけしますが、再度登録処理を行ってください。", 'eCode' => 2]);
        }
        return redirect()->route('register.success');
    }

}
