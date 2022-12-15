<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCompaniesAddIndexes2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::statement('ALTER TABLE companies add index `idx_companies_02`(`status`, `deleted_at`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	DB::statement('ALTER TABLE companies drop index idx_companies_02');
    }
}
