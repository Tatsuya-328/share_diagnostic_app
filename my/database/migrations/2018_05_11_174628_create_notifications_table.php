<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::statement(
        'CREATE TABLE IF NOT EXISTS `notifications` (
          `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `type` enum(\'admin\', \'parner\', \'front\') NOT NULL COMMENT "admin:管理画面, parner: パートナー管理画面, front:サービスサイト",
          `title` varchar(255) NOT NULL COMMENT "タイトル",
          `description` text NOT NULL COMMENT "説明文",
          `from` datetime NOT NULL COMMENT "表示開始日",
          `to` datetime COMMENT "表示終了日",
          `deleted_at` timestamp NULL DEFAULT NULL COMMENT "削除日",
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
        Schema::dropIfExists('notifications');
    }
}
