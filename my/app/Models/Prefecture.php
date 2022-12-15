<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Kyslik\ColumnSortable\Sortable;

/**
* 
*/
class Prefecture extends AppModel
{
    use SoftDeletes;
    use Sortable;

    protected $dates = ["deleted_at"];
    protected $table = "prefectures";
    protected $guarded = [];
    public $sortable = ['id', 'name', 'created_at', 'updated_at'];

    protected static function boot() {
        parent::boot();

    }

    public function cities()
    {
        return $this->hasMany('App\Models\City');
    }

    public static function getNameById($id)
    {
        return self::where('Id', $id)->first()->name;
    }

}
