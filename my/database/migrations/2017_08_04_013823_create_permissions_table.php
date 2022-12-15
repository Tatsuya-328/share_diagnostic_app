<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            'CREATE TABLE IF NOT EXISTS `permissions` (
              `id` smallint(3) UNSIGNED NOT NULL,
              `name` varchar(50) NOT NULL COMMENT "名称",
              `description` text NOT NULL COMMENT "説明文",
              `identification_name` varchar(50) NOT NULL COMMENT "プログラム側で検索用の識別名",
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
        Schema::drop('permissions');
    }
}
