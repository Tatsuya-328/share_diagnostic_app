<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            'CREATE TABLE IF NOT EXISTS `permissions_operations` (
              `id` smallint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
              `permission_id` smallint(3) UNSIGNED NOT NULL,
              `operation_id` tinyint(3) UNSIGNED NOT NULL,
              `created_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "作成日",
              `updated_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "更新日",
              PRIMARY KEY(id),
              UNIQUE KEY ui_permissions_operations_01 (permission_id, operation_id)
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
        Schema::drop('permissions_operations');
    }
}
