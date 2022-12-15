<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        # Default robots
    }
    /**
     * 検索フォーム付きリスト生成
     *    一緒に、pagerのリンク、formパラメータも生成する
     *
     * @return array $list 検索結果
     *         string $links pagerタグ
     *         array $seachColumns 検索formのパラメータ
     */
    public $defaultListOrder = 'desc';
    public $defaultListSort = 'id';
    // searchColumns=検索formのvalue値
    public function requestFormSearch($request, $query, $searchInfo, $isSortable=true, $linkAppends=[], $searchColumns=[], $additionalSearchInfos = [])
    {
        $order = $request->input('order') ?? $this->defaultListOrder;
        $sortby = $request->input('sort') ?? $this->defaultListSort;
        if (empty($linkAppends)) {
            $linkAppends = ['sort' => $sortby, 'order' => $order];
        }

        foreach ($searchInfo as $column => $option) {
            $searchColumns['search_' . $column] = null;
            if ($request->input('search_' . $column) === '0' || !empty($request->input('search_' . $column))) {
                if (is_array($option)) {
                    foreach ($option as $key => $columns) {
                        if ($key === 'likeOr') {
                            $otherColumns = explode(',', $columns);
                            $likeor['column'] = $column;
                            $likeor['otherColumns'] = $otherColumns;
                            $additionalSearchInfos['likeOr'][] = $likeor;
                        } elseif ($key == "like") {
                            $columnName = $columns;
                            $query->where($columnName, "like", '%' . $request->input('search_' . $column) . '%');
                        } elseif ($key == "=") {
                            $columnName = $columns;
                            $query->where($columnName, $request->input('search_' . $column));
                        }
                    }
                } else {
                    if ($option === '=') {
                        $query->where($column, $request->input('search_' . $column));
                    } else if ($option === "like") {
                        $query->where($column, 'like', '%' . $request->input('search_' . $column) . '%');
                    } else if ($option === "notnull") {
                        $query->whereNotNull($column);
                    } else if ($option === 'has') {
                        $tableName = $column;
                        $seachValues = [];
                        // 検索指定があるものを検索
                        if (!empty($request->get('search_' . $tableName))){
                            if (!is_array($request->get('search_' . $tableName))) {
                                $seachValues = explode(',', $request->search_articles);
                            } else {
                                $seachValues = $request->get('search_' . $tableName);
                            }
                        }
                        if (!empty($seachValues[0])) {
                            foreach ($seachValues as $value) {
                                $query->whereHas($tableName, function($query) use($value, $tableName) {
                                    $query->where($tableName . ".id", $value);
                                });
                            }
                        }
                    }
                }

                $linkAppends['search_' . $column] = $request->input('search_' . $column);
                $searchColumns['search_' . $column] = $request->input('search_' . $column);
            }
        }

        foreach ($additionalSearchInfos as $option => $optionSearchInfo) {
            if ($option === 'likeOr') {
                foreach ($optionSearchInfo as $additionalSearchInfo) {
                    $query->where(function($query) use($request, $additionalSearchInfo) {
                        $column = $additionalSearchInfo['column'];
                        $searchColumnTmpName = "search_" . $column;
                        $searchPattern = '%' . $request->$searchColumnTmpName . '%';
                        $query->orWhere($column, 'like', $searchPattern);
                        foreach ($additionalSearchInfo['otherColumns'] as $otherColumn) {
                            $query->orWhere($otherColumn, 'like', $searchPattern);
                        }
                    });
                }
            }
        }

        if ($isSortable) {
            $query->sortable([$sortby => $order]);
        }

        // 検索結果
        $list = $query->paginate();

        // pager link
        $links = $list->appends($linkAppends)->links();

        return array($list, $links, $searchColumns);
    }

     static protected function coverImageConvert($filepath)
    {
        $imagick = new \Imagick($filepath);
        self::imageOrientationRotate($imagick);
        $imagick->stripImage();
        static::imageResolution($imagick);

        $idealHeight = (int)round($imagick->getImageWidth() * 9 / 12);
        if ($idealHeight != $imagick->getImageHeight()) {
            if ($idealHeight <= $imagick->getImageHeight()) {
                // 12:9より縦が広い場合画像の上下切り取り
                $cropHeight = (int)round(($imagick->getImageHeight() - $idealHeight) / 2);
                $imagick->cropImage(
                    $imagick->getImageWidth(),
                    $imagick->getImageHeight() - $cropHeight*2,
                    0,
                    $cropHeight
                );
            } else if ($idealHeight >= $imagick->getImageHeight()) {
                // 12:9より縦が狭い場合画像の左右切り取り
                $idealWidth = (int)round($imagick->getImageHeight() * 12 / 9);
                $cropWidth = (int)round(($imagick->getImageWidth() - $idealWidth) / 2);
                $imagick->cropImage(
                    $imagick->getImageWidth() - $cropWidth*2,
                    $imagick->getImageHeight(),
                    $cropWidth,
                    0
                );
            }
        }

        if ($imagick->getImageWidth() > 1200) {
            $imagick->scaleImage(1200, 0);
        }

        $imagick->writeImage($filepath);
    }


    static protected function imageResolution(&$imagick, $num=72)
    {
        $imagick->setResolution($num, $num);
        $imagick->setImageResolution($num, $num);
        $imagick->resampleImage($num, $num, \Imagick::FILTER_UNDEFINED, 0);
    }

    static protected function imageOrientationRotate(&$imagick)
    {
        $orientation = $imagick->getImageOrientation();
        switch ($orientation) {
            case \Imagick::ORIENTATION_UNDEFINED:
                break;
            case \Imagick::ORIENTATION_TOPLEFT:
                break;
            case \Imagick::ORIENTATION_TOPRIGHT:
                $imagick->rotateImage(new \ImagickPixel(), 180);
                break;
            case \Imagick::ORIENTATION_BOTTOMRIGHT:
                $imagick->rotateImage(new \ImagickPixel(), 180);
                break;
            case \Imagick::ORIENTATION_BOTTOMLEFT:
                $imagick->rotateImage(new \ImagickPixel(), 270);
                $imagick->flopImage();
                break;
            case \Imagick::ORIENTATION_LEFTTOP:
                $imagick->rotateImage(new \ImagickPixel(), 90);
                $imagick->flopImage();
                break;
            case \Imagick::ORIENTATION_RIGHTTOP:
                $imagick->rotateImage(new \ImagickPixel(), 90);
                break;
            case \Imagick::ORIENTATION_RIGHTBOTTOM:
                $imagick->rotateImage(new \ImagickPixel(), 270);
                $imagick->flopImage();
                break;
            case \Imagick::ORIENTATION_LEFTBOTTOM:
                $imagick->rotateImage(new \ImagickPixel(), 270);
                break;
        }
    }

    /**
     * 操作ログを登録する
     *
     * @return string $key 操作しているデータ値
     *         string $action 操作しているアクション
     */
    // public function storeUserLog($text="", $action=null) {
    //     $id = Auth::id();
    //     if (empty($id)) {
    //         return;
    //     }

    //     if (empty($action)) {
    //         $action = Route::currentRouteName();
    //     }
    //     $log = new UserLog;
    //     $log->user_id = $id;
    //     $log->action = $action;
    //     $log->log = $text;
    //     $log->created_at = date("Y-m-d H:i:s");
    //     $log->save();
    // }
}
