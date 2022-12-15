<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAddUiCompaniesStations1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::statement('ALTER TABLE companies_stations add index `ui_companies_stations_01`(`company_id`, `station_id`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	DB::statement('ALTER TABLE companies_stations drop index ui_companies_stations_01');
    }
}
