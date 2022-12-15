<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsersCompaniesDropStatusAndAddDeletedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE users_companies DROP `status`");
        DB::statement("ALTER TABLE users_companies ADD `deleted_at` datetime COMMENT '削除日時' AFTER is_owner");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE users_companies ADD `status` enum('invite','confirm','leave') NOT NULL COMMENT 'invite:招待中,confirm:承認,leave:退会' AFTER company_id");
        DB::statement("ALTER TABLE users_companies DROP `deleted_at`");
    }
}
