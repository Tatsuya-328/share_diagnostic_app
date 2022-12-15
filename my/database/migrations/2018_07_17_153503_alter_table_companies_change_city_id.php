<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCompaniesChangeCityId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::statement('ALTER TABLE companies MODIFY COLUMN city_id bigint(11) COMMENT "市区町村ID"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE companies MODIFY COLUMN city_id bigint(2) COMMENT "市区町村ID"');
    }
}
