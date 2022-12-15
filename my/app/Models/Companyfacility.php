<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Companyfacility extends AppModel
{
    use SoftDeletes;

    protected $table = 'companyfacilities';
    protected $fillable = ['name', 'order', 'deleted_at'];
    protected $strip_tags = ['name'];

    /**
     * facility/orderで選択されたアイテムを保存する
     *
     * @param array $items
     * @return void
     */
    public static function updateRecords($items)
    {
        foreach ($items as $key => $id) {
            $facility = self::withTrashed()->firstOrNew(['id' => $id]);
            $facility->order = $key + 1;
            $facility->save();
        }
    }
}

