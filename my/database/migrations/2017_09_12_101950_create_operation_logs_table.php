<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            'CREATE TABLE IF NOT EXISTS `operation_logs` (
              `id` bigint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
              `admin_id` bigint(11) NOT NULL COMMENT "管理ユーザID",
              `operation` varchar(255) NOT NULL COMMENT "オペレーション名",
              `key` varchar(255) DEFAUlT NULL COMMENT "オペレーションキー",
              `created_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "作成日",
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
        //
    }
}
