<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Admin extends AppModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use SoftDeletes,
        Notifiable,
        Authenticatable,
        Authorizable,
        CanResetPassword;

    protected $dates = ['deleted_at'];
    protected $table = 'admins';

    public function permission()
    {
        return $this->belongsTo('App\Models\Permission');
    }

}
