<?php
/**
 * CDN経由の画像URLを返却します
 */
if (!function_exists('getCdnImagePath'))
{
    function getCdnImagePath($path='')
    {
        return Config::get('const.cdnImageUrl') . '/' . $path;
    }
}

/**
 * CDN経由のリサイズ画像URLを返却します
 */
if (!function_exists('getCdnResizeImagePath'))
{
    function getCdnResizeImagePath($width, $height, $path='')
    {
        return Config::get('const.cdnImageUrl') . "/resize/{$width}x{$height}/" . $path;
    }
}

/**
 * サイト内URLの絶対パスを返却します
 */
if (!function_exists('getFrontUrl'))
{
    function getFrontUrl($path='')
    {
        return Config::get('const.frontDomain') . '/' . $path;
    }
}

/**
 * サイトのドメインを持つ画像URLを返却します
 */
if (!function_exists('getFrontImagePath'))
{
    function getFrontImagePath($path='')
    {
        return getFrontUrl($path);
    }
}

/**
 * Form の value をいい感じに返します
 */
if (!function_exists('setFormValue'))
{
    function setFormValue($column, $data=null)
    {
        if (old($column) !== null) {
            return old($column);
        }
        if (!empty($data) && isset($data[$column])) {
            return $data[$column];
        }
        return null;
    }
}

/**
 * 改行コードを統一します
 */
if (!function_exists('convertEOL'))
{
    function convertEOL($string, $to = "\n")
    {
        return preg_replace("/\r\n|\r|\n/", $to, $string);
    }
}

if (! function_exists('frontRoute')) {
    function frontRoute($name, $parameters = []) {
        return route($name, $parameters, false);
    }
}


/**
 * 全ての改行コードを<br>に変換します。
 */
if (!function_exists('showText'))
{
    function showText($body)
    {
        if (!$body) {
            return null;
        }
        $body = convertEOL($body);
        $body = str_replace("\n", "<br>", $body);
        return $body;
    }
}

/**
 * 前後の空白文字と全角スペースを削除します。
 */
if (!function_exists('trimSpace'))
{
    function trimSpace($string)
    {
        $string = preg_replace('/　/', ' ', $string);
        $string = trim($string);
        return $string;
    }
}

/**
 * 住所から緯度経度を取得します。
 */
if (!function_exists('getMapGeoData'))
{
    function getMapGeoData($address)
    {
        $urlPrefix = "https://maps.googleapis.com/maps/api/geocode/json?address=";
        $address = strip_tags($address);
        $apiUrl = $urlPrefix . urlencode($address) . "&key=" . Config::get('const.googleMapApiKey');
        $json = json_decode(file_get_contents($apiUrl), true);
        if ($json['status'] != 'OK') {
            return null;
        }
        return $json['results'];
    }
}


// ログインユーザーが管理者かどうか
if (!function_exists('isAdmin'))
{
    function isAdmin()
    {
        return !empty(Auth::user()) && !empty(Auth::user()->admin);
    }
}

/**
 * 会社のステータスから返すべき文言とスタイルを表示します
 */
if (!function_exists('companyStatusLabel'))
{
    function companyStatusLabel($status, $requestType=null, $authorizeStatus=null)
    {
        $res["status"] = "";
        $res["requestType"] = "";
        $res["authorizeStatus"] = "";
        $infoLabelStyles = [
            "disable" => "warning",
            "publish" => "success",
            "none" => "primary",
            "signup" => "success",
            "update" => "warning",
            "delete" => "danger",
            "unprocessed" => "primary",
            "processing" => "primary",
            "reject" => "danger",
        ];

        // 文言、申請理由、審査状況によって文言とスタイルを適応する
        $setLabel = function($label, $key, $style=null) use ($status, $requestType, $authorizeStatus, $infoLabelStyles, &$res) {
            // 変数の値を取得
            $val = ${$key};
            $res[$key] = [
                "label" => $label,
                "style" => $style ? $style : $infoLabelStyles[$val]
            ];
        };
        // 管理者ユーザー向け
        if (isAdmin()) {
            // 非公開
            if ($status == 'disable') {
                $setLabel("非公開", "status");
                if ($requestType == 'none') {
                    if ($authorizeStatus == 'unprocessed') {
                        $setLabel("未申請", "requestType");
                    }
                } elseif ($requestType == 'signup') {
                    if ($authorizeStatus == "unprocessed") {
                        $setLabel("新規登録", "requestType");
                        $setLabel("未処理", "authorizeStatus");
                    } elseif ($authorizeStatus == "processing") {
                        $setLabel("新規登録", "requestType");
                        $setLabel("審査中", "authorizeStatus");
                    } elseif ($authorizeStatus == 'reject') {
                        $setLabel("未申請", "requestType");
                        $setLabel("却下", "authorizeStatus");
                    }
                }
            // 公開
            } else {
                $setLabel("公開", "status");
                if ($requestType) {
                    if ($requestType == "none") {
                        if ($authorizeStatus == "reject") {
                            $setLabel("未申請", "requestType");
                            $setLabel("却下", "authorizeStatus");
                        }
                    } elseif ($requestType == "update") {
                        if ($authorizeStatus == "unprocessed") {
                            $setLabel("基本情報変更", "requestType");
                            $setLabel("未処理", "authorizeStatus");
                        } elseif ($authorizeStatus == "processing") {
                            $setLabel("基本情報変更", "requestType");
                            $setLabel("審査中", "authorizeStatus");
                        } elseif ($authorizeStatus == "reject") {
                            $setLabel("未申請", "requestType", "primary");
                            $setLabel("却下", "authorizeStatus");
                        }
                    } elseif ($requestType == "delete") {
                        $setLabel("削除申請", "requestType");
                        if ($authorizeStatus == 'unprocessed') {
                            $setLabel("未処理", "authorizeStatus");
                        } elseif ($authorizeStatus == 'processing') {
                            $setLabel("審査中", "authorizeStatus");
                        } elseif ($authorizeStatus == 'reject') {
                            $setLabel("却下", "authorizeStatus");
                        }
                    }
                }
            }
        // ユーザー向け
        } else {
            // 非公開
            if ($status == 'disable') {
                $setLabel("非公開", "status");
                if ($requestType == 'none') {
                    $setLabel("未申請", "requestType");
                    if ($authorizeStatus == 'reject') {
                        $setLabel("新規登録却下", "authorizeStatus");
                    }
                } elseif ($requestType == 'signup') {
                    if ($authorizeStatus == "unprocessed") {
                        $setLabel("登録申請中", "requestType");
                    } elseif ($authorizeStatus == "processing") {
                        $setLabel("登録申請中", "requestType");
                    } elseif ($authorizeStatus == 'reject') {
                        $setLabel("未申請", "requestType", "primary");
                        $setLabel("登録申請却下", "authorizeStatus");
                    }
                }
            // 公開
            } else {
                $setLabel("公開", "status");
                if ($requestType) {
                    if ($requestType == "none") {
                        if ($authorizeStatus == "reject") {
                            $setLabel("変更申請却下", "authorizeStatus");
                        }
                    } elseif ($requestType == "update") {
                        if ($authorizeStatus == "unprocessed") {
                            $setLabel("変更申請中", "requestType");
                        } elseif ($authorizeStatus == "processing") {
                            $setLabel("変更申請中", "requestType");
                        } elseif ($authorizeStatus == "reject") {
                            $setLabel("変更申請却下", "authorizeStatus");
                        }
                    } elseif ($requestType == "delete") {
                        if ($authorizeStatus == "unprocessed" || $authorizeStatus == 'processing') {
                            $setLabel("削除申請中", "requestType");
                        } elseif ($authorizeStatus == "reject") {
                            $setLabel("削除申請却下", "authorizeStatus");
                        }
                    }
                }
            }
        }
        return $res;
    }

}
