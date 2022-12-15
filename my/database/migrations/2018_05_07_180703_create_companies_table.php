<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::statement(
    'CREATE TABLE IF NOT EXISTS `companies` (
      `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
      `name` varchar(255) COMMENT "名前",
      `status` enum(\'publish\', \'disable\') NOT NULL COMMENT "ステータス…publish:公開、disable:非公開",
      `description` text NOT NULL COMMENT "会社紹介",
      `post_code` varchar(30) NOT NULL COMMENT "郵便番号",
      `address1` varchar(255) NOT NULL COMMENT "住所(255文字)",
      `address2` varchar(255) NOT NULL COMMENT "住所(255文字)",
      `address_building` varchar(255) NOT NULL COMMENT "建物名(255文字)",
      `address_description` text COMMENT "住所説明",
      `lon` double(9,6) NOT NULL,
      `lat` double(8,6) NOT NULL,
      `tel_num` varchar(30) NOT NULL COMMENT "電話番号(30文字)",
      `prefecture_id` tinyint(2) COMMENT "都道府県ID",
      `city_id` bigint(2) COMMENT "市区町村ID",
      `main_image_id` bigint(11) UNSIGNED COMMENT "メイン画像ID",
      `list_small_image_id` bigint(11) UNSIGNED COMMENT "リスト画像ID",
      `image_ids` text COMMENT "会社画像カルーセル",
      `holiday` varchar(191) COMMENT "定休日",
      `url` varchar(191) COMMENT "ホームページURL",
      `company_name` varchar(255) COMMENT "会社名",
      `business_hours` text COMMENT "営業時間",
      `admission_fee` mediumint(11) UNSIGNED DEFAULT NULL COMMENT "入会金",
      `base_fee` text COMMENT "基本料金",
      `rental_description` text COMMENT "レンタル情報",
      `trial_description` text COMMENT "体験レッスン説明",
      `twitter` varchar(255) COMMENT "Twitter",
      `facebook` varchar(255) COMMENT "Facebook",
      `instagram` varchar(255) COMMENT "Instagram",
      `note` text COMMENT "Instagram",
      `deleted_at` timestamp NULL DEFAULT NULL COMMENT "削除日",
      `created_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "作成日",
      `updated_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "更新日",
      PRIMARY KEY(id)
    ) ENGINE=InnoDB'
    );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
