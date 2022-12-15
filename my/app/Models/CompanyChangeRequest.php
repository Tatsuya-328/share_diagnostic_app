<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\CompanyTemp;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\CompanyChangeRequestScope;
use App\Events\CompanyStatusChangeEvent;
use App\Util\CompanyFusionHandler;

class CompanyChangeRequest extends CompanyTemp
{
    use Sortable,
        SoftDeletes;

    protected $table = 'company_change_requests';
    public $sortable = ["updated_at", "id"];
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    protected $strip_tags = ["message", "name", "description", "address1", "address2", "address_building"];

    const REQUEST_TYPE_NONE = "none";
    const REQUEST_TYPE_SIGNUP = "signup";
    const REQUEST_TYPE_UPDATE = "update";
    const REQUEST_TYPE_DELETE = "delete";

    const AUTHORIZE_STATUS_UNPROCESS = 'unprocessed';
    const AUTHORIZE_STATUS_PROCESSING = 'processing';
    const AUTHORIZE_STATUS_AUTHORIZE = 'authorize';
    const AUTHORIZE_STATUS_REJECT = 'reject';

    // 申請オペレーションステータス
    const OPERATION_EDIT = "edit";
    const OPERATION_UPDATE = "update";
    const OPERATION_REGISTRATION = "registration";
    const OPERATION_UPDATE_APPLY = "updateApply";
    const OPERATION_DELETE = "delete";
    const OPERATION_CANCEL = "cancel";
    // 審査オペレーションステータス
    const OPERATION_PROCESSING = "processing";
    const OPERATION_AUTHORIZE = "authorize";
    const OPERATION_REJECT = "reject";

    const STATUS_ADMIN_LABELS = [
        "request_type" => [

        ]
    ];

    const OPERATION_CHANGE_PARAMS = [
        self::OPERATION_REGISTRATION => [
            "request_type" => self::REQUEST_TYPE_SIGNUP,
            "authorize_status" => self::AUTHORIZE_STATUS_UNPROCESS,
            "message" => "新規登録申請が完了しました。"
        ],
        self::OPERATION_UPDATE_APPLY => [
            "request_type" => self::REQUEST_TYPE_UPDATE,
            "authorize_status" => self::AUTHORIZE_STATUS_UNPROCESS,
            "message" => "変更申請が完了しました。"
        ],
        self::OPERATION_CANCEL => [
            "request_type" => self::REQUEST_TYPE_NONE,
            "message" => "申請を取り下げました。"
        ],
        self::OPERATION_DELETE => [
            "request_type" => self::REQUEST_TYPE_DELETE,
            "authorize_status" => self::AUTHORIZE_STATUS_UNPROCESS,
            "message" => "削除申請が完了しました。"
        ],
        self::OPERATION_PROCESSING => [
            "authorize_status" => self::AUTHORIZE_STATUS_PROCESSING,
            "message" => "審査を開始しました。"
        ],
        self::OPERATION_AUTHORIZE => [
            "authorize_status" => self::AUTHORIZE_STATUS_AUTHORIZE,
            "message" => "会社を承認しました。"
        ],
        // 重要項目変更は、進行中へ
        self::OPERATION_REJECT => [
            // "request_type" => self::REQUEST_TYPE_NONE,
            "authorize_status" => self::AUTHORIZE_STATUS_REJECT,
            "message" => "申請を却下しました。"
        ],
    ];

    const OPERATION_PERMISSION_PARAMS = [
        // = self::OPERATION_UPDATE
        self::OPERATION_EDIT => [
            "disable" => [
                "request_types" => [
                    self::REQUEST_TYPE_SIGNUP,
                    self::REQUEST_TYPE_DELETE
                ],
                "authorize_statuses" => [
                    self::AUTHORIZE_STATUS_UNPROCESS,
                    self::AUTHORIZE_STATUS_REJECT
                ],
            ],
            "publish" => [
                "request_types" => [
                    self::REQUEST_TYPE_UPDATE,
                    self::REQUEST_TYPE_DELETE
                ],
                "authorize_statuses" => [
                    self::AUTHORIZE_STATUS_UNPROCESS,
                    self::AUTHORIZE_STATUS_REJECT
                ],
            ]
        ],
        self::OPERATION_REGISTRATION => [
            "request_types" => [
                self::REQUEST_TYPE_NONE
            ],
            "authorize_statuses" => [
                self::AUTHORIZE_STATUS_UNPROCESS,
            ],
        ],
        self::OPERATION_UPDATE_APPLY => [
            "request_types" => [
                self::REQUEST_TYPE_NONE
            ],
            "authorize_statuses" => [
                self::AUTHORIZE_STATUS_UNPROCESS,
            ],
        ],
        self::OPERATION_CANCEL => [
            "request_types" => [
                self::REQUEST_TYPE_SIGNUP,
                self::REQUEST_TYPE_UPDATE,
                self::REQUEST_TYPE_DELETE
            ],
            "authorize_statuses" => [
                self::AUTHORIZE_STATUS_UNPROCESS,
            ],
        ],
        self::OPERATION_DELETE => [],
        self::OPERATION_PROCESSING => [
            "request_types" => [
                self::REQUEST_TYPE_SIGNUP,
                self::REQUEST_TYPE_UPDATE,
                self::REQUEST_TYPE_DELETE
            ],
            "authorize_statuses" => [
                self::AUTHORIZE_STATUS_UNPROCESS,
            ],
        ],
        self::OPERATION_AUTHORIZE => [
            "request_types" => [
                self::REQUEST_TYPE_SIGNUP,
                self::REQUEST_TYPE_UPDATE,
                self::REQUEST_TYPE_DELETE
            ],
            "authorize_statuses" => [
                self::AUTHORIZE_STATUS_PROCESSING,
            ],
        ],
        // 重要項目変更は、進行中へ
        self::OPERATION_REJECT => [
            "request_types" => [
                self::REQUEST_TYPE_SIGNUP,
                self::REQUEST_TYPE_UPDATE,
                self::REQUEST_TYPE_DELETE
            ],
            "authorize_statuses" => [
                self::AUTHORIZE_STATUS_PROCESSING,
            ],
        ],
    ];


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyChangeRequestScope);
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }

    public function prefecture()
    {
        return $this->belongsTo('App\Models\Prefecture');
    }

    public static function getEnumColumns($columnName)
    {
        $enums = parent::getEnumColumns($columnName);
        if ($columnName != 'request_type' && $columnName != 'authorize_status') {
            return $enums;
        }

        $tmpArray = [];
        foreach ($enums as $enum) {
            $showName = '';
            if ($columnName == 'request_type') {
                switch ($enum) {
                    case 'none':
                        $showName = '未申請';
                        break;
                    case 'signup':
                        $showName = '新規登録';
                        break;
                    case 'update':
                        $showName = '基本情報変更';
                        break;
                    case 'delete':
                        $showName = '削除申請';
                        break;
                    default :
                        $showName = $enum;
                        break;
                }
            } else if ($columnName == 'authorize_status') {
                switch ($enum) {
                    case 'unprocessed':
                        $showName = '未処理';
                        break;
                    case 'processing':
                        $showName = '処理中';
                        break;
                    case 'authorize':
                        $showName = '承認';
                        break;
                    case 'reject':
                        $showName = '却下';
                        break;
                    case 'hold':
                        $showName = '保留';
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

    public function getOperationStatus()
    {
        $res = array_filter(self::OPERATION_CHANGE_PARAMS, function($row) {
            return ($this->request_type == $row['request_type'] && $this->authorize_status == $row['authorize_status']);
        });
        if (is_null($res)) {
            return null;
        }
        $res = array_keys($res)[0] ?? null;
        return $res;
    }


    public function updateBase(array $data)
    {
        // 更新共通処理
        $this->saveExcuteBase($data);
    }

    public function getOperation($columnName)
    {
        return self::getEnumColumns('request_type')[$this->request_type] . ":" . self::getEnumColumns('authorize_status')[$this->authorize_status];
    }


     // 会社の中身をコピーする
    public function clone()
    {
        foreach (Company::CLONE_COLUMNS as $columnName) {
            $this->$columnName = $this->company->$columnName;
        }
        $this->save();
    }

    private function setOperationChangeStatus($params)
    {
        if (isset($params['request_type'])) {
            $this->request_type = $params['request_type'];
        }
        if (isset($params['authorize_status'])) {
            $this->authorize_status = $params['authorize_status'];
        }
    }

    // 申請保存
    public function excuteApply(array $data, $operation)
    {
        $this->setOperationChangeStatus($data);
        $this->save();
    }

    // 審査保存
    public function excuteJudge(array $params, $operation, array $data=null)
    {
        $this->setOperationChangeStatus($params);
        if ($operation == self::OPERATION_PROCESSING) {
            $this->admin_id = Auth::user()->admin_id;
        } elseif ($operation == self::OPERATION_AUTHORIZE) {
            $fusionHandler = new CompanyFusionHandler($this->company);
            // 削除承認
            if ($this->request_type == self::REQUEST_TYPE_DELETE) {
                $this->company->status = "disable";
                // Fusionテーブルから位置情報を削除
                $this->company->save();
                event(new CompanyStatusChangeEvent($this));
                $fusionHandler->deleteFusionLocation();
                $this->company->delete();
            // 公開・変更承認
            } else {
                $this->authorize_at = date('Y-m-d H:i:s', time());
                $this->company->status = "publish";
                $deleteImageIds = $this->company->getDeleteImageIds();
                $this->company->clone();
                $fusionHandler->createOrUpdateFusionLocation();
                event(new CompanyStatusChangeEvent($this, $deleteImageIds));
            }
        // 却下
        } elseif ($operation == self::OPERATION_REJECT) {
            $this->message = strip_tags($data['message']) ?? '';
            event(new CompanyStatusChangeEvent($this));
        }
        $this->save();
    }

}
