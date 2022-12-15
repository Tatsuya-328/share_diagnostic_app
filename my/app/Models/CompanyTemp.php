<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AppModel;
use App\Models\Prefecture;
use Log;

class CompanyTemp extends AppModel
{
    // 申請と会社の共通ロジックのアップデート
    // 基本情報の更新
    protected function saveExcuteBase(array $data)
    {
        $this->name = $data['name'];
        $this->description = strip_tags($data['description']);
        // 新規作成もしくは変更住所を登録する場合
        if (empty($this->id) || ($this->convertedAddress() != $this->convertedAddress($data))) {
            $this->registerLocation($data);
        }
        $this->city_id = $data['city_id'];
        $this->post_code = $data['post_code'];
        $this->prefecture_id = $data['prefecture_id'];
        $this->address1 = $data['address1'];
        $this->address2 = $data['address2'];
        $this->address_building = $data['address_building'] ?? '';
        $this->tel_num = $data['tel_num'];
        if (!empty($data['image_ids'])) {
            $this->image_ids = $data['image_ids'];
        }

        // 緯度経度を持ってくる
        $this->save();
    }

    // 位置情報を更新
    public function registerLocation($data)
    {
        $address = Prefecture::getNameById($data['prefecture_id']) . $data['address1'] . $data['address2'];
        $geoData = getMapGeoData($address);
        if (!is_null($geoData)) {
            $this->lon = $geoData[0]['geometry']['location']['lng'] ?? 0;
            $this->lat = $geoData[0]['geometry']['location']['lat'] ?? 0;
        } else {
            Log::info('failed. geoData is not exist. ID: ' . $this->id);
            $this->lon = 0;
            $this->lat = 0;
        }
    }


    // アドレスを取得します。
    public function convertedAddress($data=null) {
        if ($data) {
            return $data['post_code'] . " " . Prefecture::getNameById($data['prefecture_id']) . $data['address1'] . $data['address2'] . $data['address_building'];
        }
        return $this->post_code . " " . $this->prefecture->name . $this->address1 . $this->address2 . $this->address_building;
    }

    public function convertedImages()
    {
        $list = [];
        $imageIds = explode(',', $this->image_ids);
        foreach ($imageIds as $id) {
            if ($id) {
                $image = PartnerImage::where('id', $id)->first();
                $list[] = $image;
            }
        }
        return $list;
    }

    public function deleteImages()
    {
        $images = $this->convertedImages();
        foreach ($images as $image) {
            if (!empty($image)) {
                $image->delete();
            }
        }
    }

    public function getImageOrder(PartnerImage $image=null)
    {
        $order = 0;
        foreach ($this->imageIdList() as $key => $imageId) {
            if (!empty($image) && $image->id == $imageId) {
                break;
            }
            $order++;
        }
        return $order;
    }

    public function imageIdList()
    {
        if (empty($this->image_ids)) {
            return [];
        }
        return explode(',', $this->image_ids);
    }

    // メイン画像
    public function mainImage()
    {
        // imagesの最初の要素を返す
        return $this->convertedImages()[0];
    }

}
