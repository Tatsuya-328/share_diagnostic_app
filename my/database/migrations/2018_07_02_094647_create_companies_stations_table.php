<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::statement(
        'CREATE TABLE IF NOT EXISTS `companies_stations` (
          `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `company_id` mediumint(8) UNSIGNED NOT NULL,
          `station_id` bigint(11) UNSIGNED NOT NULL,
          `created_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "作成日",
          `updated_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "更新日",
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
        Schema::dropIfExists('companies_stations');
    }
}
