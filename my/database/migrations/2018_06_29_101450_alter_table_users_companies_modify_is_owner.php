<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsersCompaniesModifyIsOwner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE users_companies MODIFY `is_owner` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '会社の管理者権限'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE users_companies MODIFY `is_owner` tinyint(1) unsigned NOT NULL COMMENT '会社の管理者権限'");
    }
}
