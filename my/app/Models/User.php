<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Password;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\ProvisionalRegisterNotification;
use App\Notifications\VerifyRegisterNotification;
use App\Notifications\ChangeEmailNotification;
use App\Notifications\CompanyChangedNotification;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use SoftDeletes,
        Notifiable,
        Authorizable,
        CanResetPassword;

    protected $dates = ['deleted_at', 'change_email_requested_at'];
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'admin_id'];
    protected $strip_tags = ['name'];
    protected $hidden = ['password'];

    public $sortable = ['name', 'email', 'created_at', 'updated_at'];

    const STATUS_TYPE_INVITE      = "invite";
    const STATUS_TYPE_PROVISIONAL = "provisional";
    const STATUS_TYPE_MEMBER      = "member";
    const STATUS_TYPE_LEAVE       = "leave";
    const STATUS_LABELS = [
        self::STATUS_TYPE_INVITE      => '招待中',
        self::STATUS_TYPE_PROVISIONAL => '仮登録',
        self::STATUS_TYPE_MEMBER      => '正会員',
        self::STATUS_TYPE_LEAVE       => '退会',
    ];

    const CONVERT_PASSWORD_RESPONSE_MESSAGES = [
        Password::RESET_LINK_SENT => 'パスワード変更のメッセージを送信しました。',
        Password::PASSWORD_RESET => 'パスワードをリセットしました。',
        Password::INVALID_USER => 'ユーザーが見つかりませんでした。',
        Password::INVALID_TOKEN => 'このURLは無効です。お手数ですが、再度パスワード再登録処理を実行してください。',
    ];

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }

    public function companies()
    {
        return $this->belongsToMany('App\Models\Company', 'users_companies')->withTimestamps();
    }

    public function usersCompanies()
    {
        return $this->hasMany('App\Models\UsersCompany');
    }

    // 閲覧権限のあるID
    public function getCanViewIds($idColumnName='user_id')
    {
        return $this->usersCompanies->pluck('company_id')->toArray();
    }

    public static function getMailErrorMessage($e)
    {
        $message = "登録に失敗しました。お手数ですが、再度やり直して下さい。";
        $action = Route::currentRouteName();
        if ($action == "user.email.change") {
            switch(get_class($e)) {
                case "GuzzleHttp\Exception\ClientException":
                    $message = "メールアドレス変更登録に失敗しました。お手数ですが、入力されたメールアドレスが正しく存在するかを確認した上で、再度やり直して下さい。";
                    break;
                case "ErrorException":
                    $message = "メールアドレス変更登録に失敗しました。お手数ですが、再度お試しください。問題が解決しない場合は、最下部にあるお問い合わせよりご連絡ください。";
                    break;
                default:
                    break;
            }
        } elseif ($action == "provisional") {
            switch(get_class($e)) {
                case "GuzzleHttp\Exception\ClientException":
                    $message = "仮登録に失敗しました。お手数ですが、入力されたメールアドレスが正しく存在するかを確認した上で、再度やり直して下さい。";
                    break;
                case "ErrorException":
                    $message = "仮登録に失敗しました。お手数ですが、再度お試しください。問題が解決しない場合は、最下部にあるお問い合わせよりご連絡ください。";
                    break;
                default:
                    break;
            }
        }
        return $message;
    }

    public static function getByEmail($email, $isMember=true)
    {
        $query = self::where("email", $email);
        if ($isMember) {
            $query->where('status', self::STATUS_TYPE_MEMBER);
        }
        return $query->first();
    }

    // 編集権限のあるID
    // public function getCanEditIds($idColumnName='user_id')
    // {
    //     $ids = [];
    //     foreach ($this->usersCompanies as $companyUser) {
    //         if ($companyUser->is_owner == true) {
    //             $ids[] = $companyUser->$idColumnName;
    //         }
    //     }
    //     return $ids;
    // }

    /**
     *  ユーザ本登録用トークン生成メソッド
     *
     *  先頭がアルファベットではじまるランダム英数字を生成する
     *  生成後、すでに存在するIDかどうかを確認する。この際大文字小文字を区別する(照合順序にutf8_binを採用)。
     *
     * @return string $user_id 生成したユーザID
     */
    static public function generateVerifyToken()
    {
        do {
            $token = str_random(config('const.registerTokenLength'));
        } while(!is_null(User::where("email_verify_token", $token)->first()));
        return $token;
    }

    // 編集権限のあるID
    // public function getCanEditIds($idColumnName='user_id')
    // {
    //     $ids = [];
    //     foreach ($this->usersCompanies as $companyUser) {
    //         if ($companyUser->is_owner == true) {
    //             $ids[] = $companyUser->$idColumnName;
    //         }
    //     }
    //     return $ids;
    // }


    public static function create(array $data)
    {
        // 仮登録で作成
        $res = self::firstOrNew([
            'email' => $data['email'],
        ]);
        $res->name = $data['name'];
        $res->password = bcrypt($data['password']);
        $res->status = $data["status"];
        $res->email_verify_token = self::generateVerifyToken();
        $res->provisional_registered_at = date('Y-m-d H:i:s', time());
        $res->save();
        return $res;
    }

    /**
     * Create a member user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

    public static function register(array $data)
    {
        $res = $data['user'];
        $res->status = $data["status"];
        $res->admin_id = $data['adminId'];
        $res->email_verify_token = $data['email_verify_token'];
        $res->save();
        return $res;
    }

    /**
     * Send provisional register notification.
     *
     * @return void
     */
    public function provisionalRegisterNotification()
    {
        $this->notify(new ProvisionalRegisterNotification($this->email_verify_token));
    }

    public function verifyRegisterNotification()
    {
        $this->notify(new VerifyRegisterNotification());
    }

    /**
     * メールアドレス変更の受付通知
     *
     * @return void
     */
    public function changeEmailNotification()
    {
        $this->notify(new ChangeEmailNotification());
    }

    /**
     * 会社ステータスの変更の通知
     *
     * @return void
     */
    public function changeCompanyStatusNotification($companyChangeRequest)
    {
        $this->notify(new CompanyChangedNotification($companyChangeRequest));
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * 名前＋emailの初め何文字かを出力（ユニーク表示にするため）
     * ex) abcde(fghij**@***)
     *
     * @return sring
     */
    public function nameWithEmail($email_length = 5) {
        return $this->name . '(' . substr($this->email, 0, $email_length) . '*****)';
    }

    /**
     * 正会員かどうか
     *
     * @return string
     */
    public function isStatusMember() {
        return $this->status === self::STATUS_TYPE_MEMBER;
    }

    /**
     * ステータスを表示用の名前で返す
     *
     * @return string
     */
    public function statusLabel() {
        return self::STATUS_LABELS[$this->status];
    }

    /**
     * 会社の編集メンバーかどうか
     */
    public function isCompanyMember($company_id)
    {
        return $this->usersCompanies->where('company_id', $company_id)->count() > 0;
    }

    /**
     * 会社のオーナーかどうか
     */
    public function isCompanyOwner($company_id)
    {
        return $this->usersCompanies->where('is_owner', true)
            ->where('company_id', $company_id)
            ->count() > 0;
    }

    public function leave()
    {
        $this->status = self::STATUS_TYPE_LEAVE;
        $this->deleted_at = date_create();
        $this->save();
    }

}


