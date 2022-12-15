<?php

namespace App\Models;

use DB;
use Log;

class CompaniesStyle extends AppModel
{
    protected $table = 'companies_styles';
    protected $fillable = ['name', 'order', 'deleted_at'];

    /**
     * companies/{company}/styles/orderで選択されたアイテムを保存する
     *
     * @param array $items
     * @return bool
     */
    public static function updateRecords($items, $companyId)
    {
        foreach ($items as $key => $styleId) {
            $companiesStyle = self::firstOrNew(['style_id' => $styleId, 'company_id' => $companyId]);
            $companiesStyle->order = $key + 1;
            $companiesStyle->save();
        }
    }
}

