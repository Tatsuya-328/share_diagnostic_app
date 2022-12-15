<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCompanyChangeRequestsModifyAuthorizeStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::statement('alter table `company_change_requests` modify authorize_status enum(\'unprocessed\', \'processing\', \'authorize\', \'reject\', \'hold\') NOT NULL COMMENT "審査状況…unprocessed：未処理,processing：審査中,authorize：承認,reject：却下,hold：保留"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	DB::statement('alter table `company_change_requests` modify authorize_status enum(\'unprocessed\', \'processing\', \'authorize\', \'reject\', \'hold\') NOT NULL COMMENT "承認状況…unprocessed：未処理,processing：処理中,authorize：承認,reject：却下,hold：保留"');
    }
}
