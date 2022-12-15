<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use App\Models\CompanyChangeRequest;
use App\Models\PartnerImage;
use Auth;
use App\Util\CompanyFusionHandler;

class Company extends CompanyTemp
{
    use Sortable,
        SoftDeletes;

    public $sortable = ["status", "updated_at", "request_type", "authorize_status"];
    protected $guarded = [];
    protected $strip_tags = ["name", "holiday", "url", "twitter", "facebook", "instagram", "company_name", "business_hours", "admission_fee", "base_fee", "rental_description", "trial_description", "address_description", "address1", "address2", "address_building", "description"];

    const STATUS_DISABLE = "disable";
    const STATUS_PUBLISH = "publish";

    const CLONE_COLUMNS = ["name", "description", "post_code", "prefecture_id", "address1", "address2", "address_building", "lon", "lat", "city_id", "tel_num", "image_ids"];
    const BASE_IMAGE_FILEPATH = "filepath";
    const BASE_IMAGE_ALT = "alt";

    const OPERATION_STATUS_CREATE = "create";
    const OPERATION_STATUS_REGISTER = "register";

    public function companyChangeRequest()
    {
        return $this->hasOne('App\Models\CompanyChangeRequest');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'users_companies')->withTimestamps();
    }

    public function usersCompanies()
    {
        return $this->hasMany('App\Models\UsersCompany');
    }

    public function prefecture()
    {
        return $this->belongsTo('App\Models\Prefecture');
    }

    public function stations()
    {
        return $this->belongsToMany('App\Models\Station', "companies_stations")->withTimestamps();
    }

    public function styles()
    {
        return $this->belongsToMany('App\Models\Style', "companies_styles")->orderBy('companies_styles.order', 'asc')->withTimestamps();
    }

    public function companiesStyles()
    {
        return $this->hasMany('App\Models\CompaniesStyle');
    }

    public function companyfacilities()
    {
        return $this->belongsToMany('App\Models\Companyfacility', "companies_companyfacilities")->withTimestamps();
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function($company) {
            $company->deleteImages();
            if (!empty($company->companyChangeRequest)) {
                $company->companyChangeRequest->deleteImages();
                $company->companyChangeRequest()->delete();
            }
        });
    }


    public function usersCompaniesExceptAdmin() {
        return $this->usersCompanies->filter(function($record) {
            return empty($record->user->admin_id);
        });
    }

    public static function getEnumColumns($columnName)
    {
        $enums = parent::getEnumColumns($columnName);
        if ($columnName != 'status') {
            return $enums;
        }

        $tmpArray = [];
        foreach ($enums as $enum) {
            $showName = '';
            if ($columnName == 'status') {
                switch ($enum) {
                    case 'publish':
                        $showName = '公開';
                        break;
                    case 'disable':
                        $showName = '非公開';
                        break;
                    default :
                        $showName = $enum;
                        break;
                }
            }
            $tmpArray[$enum] = $showName;
        }
        $enums = $tmpArray;
        return $enums;
    }

    // 最新の
    public function current()
    {
        return (!empty($this->companyChangeRequest) ? $this->companyChangeRequest : $this);
    }


    public function currentImages()
    {
        return $this->current()->convertedImages();
    }

    public function currentImageOrder(PartnerImage $image=null)
    {
        return $this->current()->getImageOrder($image);
    }

    public function makeDeleteRequest()
    {
        $this->saveCompanyChangeRequest();
    }

    // companyChangeRequestの作成・更新
    private function saveCompanyChangeRequest($base=null)
    {
        // companyChangeRequestがなかったら新規作成
        if (empty($this->companyChangeRequest)) {
            $this->companyChangeRequest = new CompanyChangeRequest;
            $this->companyChangeRequest->company_id = $this->id;
            $this->companyChangeRequest->user_id = Auth::id();
            $this->companyChangeRequest->request_type = CompanyChangeRequest::REQUEST_TYPE_NONE;
            $this->authorize_status = CompanyChangeRequest::AUTHORIZE_STATUS_UNPROCESS;
            $this->companyChangeRequest->clone(); // Company → ChangeRequestコピー
        // 却下の場合編集時に審査状況を未処理に戻す
        } elseif ($this->companyChangeRequest->authorize_status == CompanyChangeRequest::AUTHORIZE_STATUS_REJECT) {
            $this->companyChangeRequest->request_type = CompanyChangeRequest::REQUEST_TYPE_NONE;
            $this->companyChangeRequest->authorize_status = CompanyChangeRequest::AUTHORIZE_STATUS_UNPROCESS;
        }
        if (!empty($base)) {
            $this->companyChangeRequest->updateBase($base);
        }
    }

    // 基本情報の更新
    public function updateBase(array $data)
    {
        // 新規作成もしくは非公開の場合
        if (empty($this->id) || $this->status == Company::STATUS_DISABLE) {
            $this->status = "disable";
            $this->saveExcuteBase($data);
        }

        $this->saveCompanyChangeRequest($data);
        // ログに登録する用のインスタンスを返す
        return $this;
    }


    // 画像更新と、順番に該当する画像のID入れ替え
    public function updateImage($newImage, $oldImage)
    {
        // companyChangeRequestの作成（ない場合のみ作成）
        $this->saveCompanyChangeRequest();
        // 編集する画像の順番を取得
        $order = $this->companyChangeRequest->getImageOrder($oldImage);
        $imageIdList = $this->companyChangeRequest->imageIdList();
        $imageIdList[$order] = $newImage->id;
        $this->saveExcuteImageIds($imageIdList);
    }

    // 並び替え実装
    public function updateImagesOrder($items, $companyId)
    {
        $list = [];
        foreach ($items as $key => $imageId) {
            $list[] = $imageId;
        }
        // companyChangeRequestの作成（ない場合のみ作成）
        $this->saveCompanyChangeRequest();
        $this->saveExcuteImageIds($list);
    }

    public function destroyImage($image)
    {
        // companyChangeRequestの作成（ない場合のみ作成）
        $this->saveCompanyChangeRequest();
        $deleteId = [$image->id];
        // 削除した画像ID郡を取得
        $imageIds = array_diff($this->companyChangeRequest->imageIdList(), $deleteId);
        $this->saveExcuteImageIds($imageIds);
    }

    // image_idsの共通保存処理
    private function saveExcuteImageIds($list)
    {
        $imageIds = implode(',', $list);
        $this->companyChangeRequest->image_ids = $imageIds;
        $this->companyChangeRequest->save();

        // 非公開の場合は、会社も保存
        if ($this->status == Company::STATUS_DISABLE) {
            $this->image_ids = $imageIds;
            $this->save();
        }
    }


    // 変更リクエストの基本情報と中身を中身を会社にコピーする
    public function clone()
    {
        $params = [];
        foreach (self::CLONE_COLUMNS as $columnName) {
            $params[$columnName] = $this->companyChangeRequest->$columnName;
        }
        $this->saveExcuteBase($params);
    }

    public function getDeleteImageIds()
    {
        return array_diff($this->imageIdList(), $this->companyChangeRequest->imageIdList());
    }

    // 詳細情報１を更新
    public function updateDetail1(array $data)
    {
        $this->holiday = $data['holiday'] ?? '';
        $this->url = $data['url'] ?? '';
        $this->twitter = $data['twitter'] ?? '';
        $this->facebook = $data['facebook'] ?? '';
        $this->instagram = $data['instagram'] ?? '';
        $this->company_name = $data['company_name'] ?? '';
        $this->business_hours = $data['business_hours'] ?? '';
        $this->admission_fee = $data['admission_fee'] ?? '';
        $this->base_fee = $data['base_fee'] ?? '';
        $this->rental_description = $data['rental_description'] ?? '';
        $this->trial_description = $data['trial_description'] ?? '';
        // Fusionテーブル処理
        $fusionHandler = new CompanyFusionHandler($this);
        $fusionHandler->createOrUpdateFusionDetail1();
        $this->save();
    }

    // 詳細情報2を更新
    public function updateDetail2(array $data)
    {
        $this->address_description = $data['address_description'] ?? "";
        $stations = $data['stations'] ?? [];
        // 消えたものに関しても関連せず自動的に同期
        $this->stations()->sync($stations);
        $this->save();
    }

    // スタイル更新
    public function updateStyles($requestIds)
    {
        $companyStyleIds = [];
        $order = 1;
        foreach ($this->styles as $style) {
            if (in_array($style->id, $requestIds)) {
                $companyStyleIds[$style->id] = ['order' => $order];
                $order++;
            }
        }
        $addStyleIds = array_diff($requestIds, $this->styles->pluck('id')->toArray());
        foreach ($addStyleIds as $id) {
            $companyStyleIds[$id] = ['order' => $order];
            $order++;
        }
        $this->styles()->detach();
        $this->styles()->attach($companyStyleIds);
    }

    // 会社または会社の指定したカラムに公開後の変更があるかどうか
    public function isRequestChanged($columnName="", $isMethod=false)
    {
        if ($this->status != self::STATUS_PUBLISH) {
            return false;
        }
        // 変更リクエストがない場合
        if (empty($this->companyChangeRequest)) {
            return false;
        // どれか一つでも変更があった場合
        } elseif (!$columnName) {
            foreach (self::CLONE_COLUMNS as $column) {
                if ($this->$column != $this->companyChangeRequest->$column) {
                    return true;
                }
            }
            return false;
        }
        // メソッドとして呼び出す場合
        if ($isMethod) {
            return $this->companyChangeRequest->$columnName() != $this->$columnName();
        }
        // 指定したカラムの変更があった場合
        return $this->companyChangeRequest->$columnName != $this->$columnName;
    }

    // 画像が存在するかどうか（申請のバリデーション用）
    public function isImageExist()
    {
        // 公開
        if ($this->status == self::STATUS_PUBLISH) {
            // 公開中で変更申請の画像がない場合
            if (!empty($this->companyChangeRequest) && !count($this->companyChangeRequest->convertedImages())) {
                 return false;
            }
        // 非公開
        } else {
            // 作成中
            if (empty($this->companyChangeRequest)) {
                return false;
            }
            // 申請中の画像がない場合
            if (!count($this->companyChangeRequest->convertedImages())) {
                return false;
            }
        }
        return true;
    }


    // 申請中かどうかの取得
    public function isProcessiong()
    {
        return !empty($this->companyChangeRequest) && $this->companyChangeRequest->authorize_status == CompanyChangeRequest::AUTHORIZE_STATUS_PROCESSING;
    }

    // エラーメッセージを表示するか
    public function isShowMessage()
    {
        if (empty($this->companyChangeRequest) || !$this->companyChangeRequest->message) {
            return false;
        }
        // 却下もしくは、メッセージありで、未申請の場合
        return $this->companyChangeRequest->authorize_status == CompanyChangeRequest::AUTHORIZE_STATUS_REJECT || ($this->companyChangeRequest->request_type == CompanyChangeRequest::REQUEST_TYPE_NONE && $this->companyChangeRequest->authorize_status == CompanyChangeRequest::AUTHORIZE_STATUS_UNPROCESS);
    }

    // city_idを持つかどうか
    public function notHasCityId()
    {
        return (!empty($this->companyChangeRequest) && empty($this->companyChangeRequest->city_id));
    }

}
