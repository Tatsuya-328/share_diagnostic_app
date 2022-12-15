<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCompanyChangeRequestsAddClumnLatAndLon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE company_change_requests ADD COLUMN lon double(9,6) NOT NULL COMMENT "経度" AFTER address_building');
        DB::statement('ALTER TABLE company_change_requests ADD COLUMN lat double(9,6) NOT NULL COMMENT "緯度" AFTER lon');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE company_change_requests DROP COLUMN lon');
        DB::statement('ALTER TABLE company_change_requests DROP COLUMN lat');
    }
}
