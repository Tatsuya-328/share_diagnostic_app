<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRailroadCompaniesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::statement(
        'CREATE TABLE IF NOT EXISTS `railroad_companies` (
          `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `code` varchar(7) NOT NULL  COMMENT "事業者コード",
          `rr_code` varchar(7) NOT NULL  COMMENT "鉄道コード",
          `name` varchar(255) NOT NULL  COMMENT "事業者名(一般)",
          `name_kana` varchar(255) COMMENT "事業者名(カナ)",
          `name_formal` varchar(255) COMMENT "事業者名(正式名称)",
          `name_short` varchar(255) COMMENT "事業者名(略称)",
          `url` text COMMENT "Webサイト",
	  `type` tinyint(2) COMMENT "事業者区分",
          `status` enum(\'running\', \'before\', \'stop\') COMMENT "状態 ..running:運用中..before:運用前..stop:廃止",
          `sort` bigint(11) UNSIGNED COMMENT "並び順",
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
        Schema::dropIfExists('railroad_companies');
    }
}
