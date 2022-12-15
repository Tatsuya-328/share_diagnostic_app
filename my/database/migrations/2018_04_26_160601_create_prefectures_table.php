<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrefecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::statement(
            'CREATE TABLE IF NOT EXISTS `prefectures` (
              `id` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT,
              `code` varchar(2) NOT NULL COMMENT "都道府県コード（国土交通省）APIのキーID",
              `name` varchar(50) NOT NULL COMMENT "名前",
              `name_en` varchar(50) NOT NULL COMMENT "名前(英語)",
              `deleted_at` timestamp NULL DEFAULT NULL COMMENT "削除日",
              `created_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "作成日",
              `updated_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "更新日",
              PRIMARY KEY(id),
              UNIQUE KEY ui_prefectures_01 (`code`)
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
        Schema::dropIfExists('prefectures');
    }
}
