<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AppModel extends Model
{
    protected $strip_tags = []; // save,update時にstrip_tagsを通す

    public static function getEnumColumns($columnName)
    {
        $table = (new static)->getTable();
        $value = DB::select(DB::raw("show columns from $table like '$columnName'"));
        if (empty($value) && strpos($value->Type, 'enum')!==0) {
            return false;
        }

        $value = array_shift($value);
        $matches = [];
        preg_match('/^enum\(([\S]+)\)$/', $value->Type, $matches);

        $enums = explode(',', $matches[1]);
        foreach ($enums as &$enum) {
            $enum = trim($enum, "'");
        }
        return $enums;
    }

    /**
     * @override
     */
    public function update(array $attributes = [], array $options = [])
    {
        foreach ($this->strip_tags as $column) {
            $val = $this->{$column};
            $this->{$column} = strip_tags($val);
        }

        return parent::update($attributes, $options);
    }

    /**
     * @override
     */
    public function save(array $options = [])
    {
        foreach ($this->strip_tags as $column) {
            $val = $this->{$column};
            $this->{$column} = strip_tags($val);
        }

        return parent::save($options);
    }
}
