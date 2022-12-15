<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersCompaniesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::statement(
        'CREATE TABLE IF NOT EXISTS `users_companies` (
          `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `user_id` bigint(11) UNSIGNED NOT NULL COMMENT "ユーザーID",
          `company_id` mediumint(8) UNSIGNED NOT NULL COMMENT "会社ID",
          `status` enum(\'invite\', \'confirm\', \'leave\') NOT NULL COMMENT "invite: 招待中, confirm: 承認, leave: 退会",
          `is_owner` tinyint(1) UNSIGNED NOT NULL COMMENT "会社の管理者権限",
          `created_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "作成日",
          `updated_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "更新日",
	  unique ui_users_companies_01(user_id, company_id),
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
        Schema::dropIfExists('users_companies');
    }
}
