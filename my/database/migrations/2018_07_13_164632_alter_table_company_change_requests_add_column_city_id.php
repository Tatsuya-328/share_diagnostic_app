<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCompanyChangeRequestsAddColumnCityId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::statement('ALTER TABLE company_change_requests ADD COLUMN city_id bigint(11) COMMENT "市区町村ID" AFTER prefecture_id');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	DB::statement('ALTER TABLE company_change_requests DROP COLUMN city_id');
    }
}
