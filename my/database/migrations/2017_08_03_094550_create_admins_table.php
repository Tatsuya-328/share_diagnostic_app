<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            'CREATE TABLE IF NOT EXISTS `admins` (
              `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT,
              `email` varchar(100) NOT NULL COMMENT "メールアドレス",
              `password` varchar(255) NOT NULL COLLATE utf8mb4_bin COMMENT "パスワード",
              `name` varchar(50) NOT NULL COMMENT "名前",
              `permission_id` smallint(3) UNSIGNED NOT NULL COMMENT "パーミッションID",
              `writer_id` bigint(11) UNSIGNED NOT NULL COMMENT "デフォルトのライターID",
              `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
        Schema::drop('admins');
    }
}
