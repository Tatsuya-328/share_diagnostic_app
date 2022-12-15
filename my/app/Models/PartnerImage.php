<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class PartnerImage extends AppModel
{
    use SoftDeletes;

    protected $tables = "partner_images";
    protected $guarded = [];
    protected $dates = ['deleted_at'];
    protected $strip_tags = ["alt"];

    const IMAGE_KIND_STUDIO = "company";

    // 会社画像を取得
    public static function getCompanyImages($id)
    {
        $result = self::where([
            'company_id' => $id])
        ->orderBy("id", "asc")
        ->get();

        return $result;
    }

    // 取得したimageを順番に格納
    public static function getCompanyImageList($id)
    {
        $images = self::getCompanyImages($id);
        $list = [];
        foreach ($images as $key => $image) {
            $list[$key + 1] = $image;
        }
        return $list;
    }

    public function storeCompany(UploadedFile $file)
    {
        $this->store($file, self::IMAGE_KIND_STUDIO, true);
    }

    private function store(UploadedFile $file, $kind=self::IMAGE_KIND_STUDIO, $isAddPathId=false)
    {
        $info = getimagesize($file->getPathname());
        $this->width = $info[0];
        $this->height = $info[1];

        $uploadPath = $kind;
        if ($isAddPathId) {
            $this->filepath = 'dummy';
            $this->save();
            $uploadPath = $kind . '/' . $this->id;
        }
        config(['filesystems.disks.s3.options.Expires' => date('r', time()+31536000)]);
        $this->filepath = $file->store($uploadPath, 's3');
    }

    public static function deleteList($ids)
    {
        foreach ($ids as $id) {
            self::where('id', $id)->delete();
        }
    }
}
