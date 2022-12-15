<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Notifications\InviteCompanyMemberNotification;

class UsersCompany extends AppModel
{
    use SoftDeletes, Notifiable;

    protected $table = 'users_companies';
    protected $fillable = ['user_id', 'company_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company')->withTrashed();
    }

    /**
     * 招待メール送信
     *
     * @return void
     */
    public function inviteCompanyMemberNotification()
    {
        $this->notify(new inviteCompanyMemberNotification());
    }

}
