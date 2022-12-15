<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Log;
use Auth;
use Illuminate\Support\Facades\Route;

class UserLog extends AppModel
{

    use Sortable;

    protected $table = "user_logs";
    protected $fillable = ["id", "user_id", "action", "log", "created_at"];
    protected $strip_tags = ["log"];

    public $sortable = ["user_id", "created_at"];

    // Eventと対になる、ログ登録用のアクション名
    const SEARCH_ACTIONS = [
        'companies.create.exec' => '会社新規作成',
        'companies.base.exec' => '会社基本情報更新',
        'companies.detail1.exec' => '会社詳細1更新',
        'companies.detail2.exec' => '会社詳細2更新',
        'companies.image.exec' => '会社画像追加',
        'companies.images.update.exec' => '会社画像更新',
        'companies.image.delete' => '会社画像削除',
        'companies.images.order.exec' => '会社画像並び替え',
        'companies.styles.exec' => '会社スタイル設定',
        'companies.styles.order.exec' => '会社スタイル並び替え',
        'companies.search.exec' => '会社検索条件設定',
        'companies.apply.registration' => '新規登録申請',
        'companies.apply.cancel' => '申請取りやめ',
        'companies.apply.update' => '変更申請',
        'companies.apply.delete' => '削除申請',
        'companies.judge.processing' => '会社審査開始',
        'companies.judge.authorize' => '会社承認',
        'companies.judge.reject' => '会社却下',
        'companies.users.invite' => '会社ユーザー招待',
        'companies.users.remove' => '会社ユーザー削除',
        'facility.create' => '条件作成',
        'facility.store' => '条件変更',
        'facility.delete' => '条件表示・非表示',
        'facility.order.store' => '条件並び替え',
        'style.store' => 'スタイル保存',
        'style.delete' => 'スタイル表示・非表示',
        'user.memo.store' => 'アカウント変更'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


    /**
     * 操作ログを登録する
     *
     * @return string $key 操作しているデータ値
     *         string $action 操作しているアクション
     */
    public static function store($request, $data=null) {
        $id = Auth::id() ?? null;
        if (empty($id)) {
            return;
        }
        if (empty($data)) {
            $data = $request->all();
        }
        $action = Route::currentRouteName();
        $log = new UserLog;
        $company = $request->route()->parameter('company');
        if (!empty($company)) {
            $log->company_id = $company->id;
        }
        $log->action = $action;
        $log->log = json_encode($data);
        $log->user_id = $id;
        $log->created_at = date("Y-m-d H:i:s");
        $log->save();
    }

    public function getJsonToText()
    {
        $data = json_decode($this->log);
        $text = "";
        if (!empty($data)) {
            $text = "{\n";
            foreach ($data as $key => $value) {
                if (!is_string($value)) {
                    if (is_array($value)) {
                        $value = json_encode($value);
                    } elseif (is_null($value)) {
                        $value = "";
                    // 配列でないオブジェクトの場合は念のため通知する
                    } else {
                        Log::info("warning. log coundn't get value because value is not string. id: " . $this->id . ", key:" . $key . ", value:");
                        Log::info($value);
                        continue;
                    }
                }
                $line = $key . ": " . $value . ",\n";
                $text .= $line;
            }
            $text .= "}";
        }

        return $text;
    }
}
