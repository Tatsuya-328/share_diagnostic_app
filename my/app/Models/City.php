<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;


class City extends Model
{
    const SUFFIXES = ["市", "区", "町", "村"];

    // 市区町村IDを取得
    public static function getIdByAddress($address, $prefecture)
    {
        // 未指定の場合は、nullを返す
        if (empty($prefecture)) {
            return null;
        }
        $address = str_replace($prefecture->name, '', $address);
        $id = self::getConvertedId($address, $prefecture);

        // 郡という文字列があった場合
        if (!$id && strpos($address, "郡") !== false) {
            $str = mb_substr($address, 0, mb_strpos($address, "郡") + 1);
            $address = str_replace($str, "", $address);
            $id = self::getConvertedId($address, $prefecture);
        }

        if (!$id) {
            // 駅で市区町村IDがないものは決め打ちで入れる
            if (mb_strpos($address, "ヶ") !== false) {
                $address = str_replace("ヶ", "ケ", $address);
                $id = self::getConvertedId($address, $prefecture);
            }
        }

        return $id;
    }

    /**
    / 「市区町村」を住所から文字列検索をかける
    / 例 四日市市 -> 四日市と四日市市で2回検索
    **/
    private static function getConvertedId($address, $prefecture)
    {
        $suffixes = self::SUFFIXES;
        foreach ($suffixes as $suffix) {
            $offset = 0;
            while ($offset = mb_strpos($address, $suffix,  $offset + 1)) {
                $str = mb_substr($address, 0, $offset + 1);
                $city = self::getByInfo($prefecture->id, $str) ?? self::getByInfo($prefecture->id, mb_substr($str, 0, -1));
                if (!empty($city)) {
                    return $city->id;
                }
            }
        }
        return null;
    }

    public static function getByInfo($prefectureId, $cityName)
    {
        return self::where('prefecture_id', $prefectureId)
                ->where("name", $cityName)
                ->first();
    }

}
