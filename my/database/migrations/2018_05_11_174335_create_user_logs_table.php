<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::statement(
        'CREATE TABLE IF NOT EXISTS `user_logs` (
          `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `user_id` bigint(11) UNSIGNED NOT NULL COMMENT "ユーザーID",
          `action` varchar(255) NOT NULL COMMENT "controller名-action名",
          `log` text NOT NULL COMMENT "ログ",
          `created_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "作成日",
          `updated_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "更新日",
          index idx_user_logs_01(user_id),
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
        Schema::dropIfExists('user_logs');
    }
}
