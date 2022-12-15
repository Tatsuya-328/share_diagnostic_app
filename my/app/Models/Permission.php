<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Permission extends AppModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'permissions';


    protected static function boot() {
        parent::boot();
    }

    public function admin()
    {
        return $this->hasMany('App\Models\Admin');
    }

}
