<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyChangeRequestsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::statement(
      'CREATE TABLE IF NOT EXISTS `company_change_requests` (
        `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `company_id` mediumint(11) UNSIGNED NOT NULL COMMENT "会社ID",
        `user_id` bigint(11) UNSIGNED NOT NULL COMMENT "ユーザID",
        `admin_id` bigint(11) UNSIGNED COMMENT "管理者ID",
        `request_type` enum(\'none\', \'signup\', \'update\', \'delete\') NOT NULL COMMENT "申請理由…none: 未申請,signup: 新規登録,update: 重要項目変更,delete: 削除申請",
        `authorize_status` enum(\'unprocessed\', \'processing\', \'authorize\', \'reject\', \'hold\') NOT NULL COMMENT "承認状況…unprocessed：未処理,processing：処理中,authorize：承認,reject：却下,hold：保留",
        `name` varchar(255) COMMENT "名前",
        `description` text COMMENT "会社紹介",
        `post_code` varchar(30) COMMENT "郵便番号。ハイフンなし",
        `prefecture_id` tinyint(2) COMMENT "都道府県ID",
        `address1` varchar(255) NOT NULL COMMENT "住所(255文字)",
        `address2` varchar(255) NOT NULL COMMENT "住所(255文字)",
        `address_building` varchar(255) NOT NULL COMMENT "建物名(255文字)",
        `tel_num` varchar(30) COMMENT "電話番号(30文字)",
        `message` text COMMENT "非承認時のメッセージ",
        `authorize_at` datetime DEFAULT NULL COMMENT "承認日",
        `deleted_at` timestamp NULL DEFAULT NULL COMMENT "削除日",
        `created_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "作成日",
        `updated_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "更新日",
        index company_change_requests_idx1(company_id),
        index company_change_requests_idx2(authorize_at),
        index company_change_requests_idx3(deleted_at),
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
        Schema::dropIfExists('company_change_requests');
    }
}
