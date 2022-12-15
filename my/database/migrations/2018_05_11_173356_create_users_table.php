<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::statement(
        'CREATE TABLE IF NOT EXISTS `users` (
          `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `email` varchar(100) NOT NULL COMMENT "メールアドレス",
          `change_email` varchar(100) COMMENT "変更メールアドレス",
          `password` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT "パスワード",
          `name` varchar(50) NOT NULL COMMENT "名前",
          `status` enum(\'invite\', \'provisional\', \'member\', \'leave\') NOT NULL COMMENT "invite: 招待中, provisional: 仮登録, member: 正会員, leave: 退会",
          `admin_id` bigint(11) UNSIGNED COMMENT "管理者ID",
          `memo` text COMMENT "運用用メモ",
          `email_verify_token` varchar(191) COMMENT "email用トークン",
          `remember_token` varchar(100) DEFAULT NULL COMMENT "ログイン情報保存用のクッキー",
          `is_ban` tinyint(1) UNSIGNED DEFAULT \'0\' COMMENT "禁止フラグ",
          `provisional_registered_at` datetime NOT NULL DEFAULT \'1000-01-01 00:00:00\' COMMENT "仮登録完了日時",
          `change_email_requested_at` datetime COMMENT "メール変更リクエスト日時",
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
        Schema::dropIfExists('users');
    }
}
