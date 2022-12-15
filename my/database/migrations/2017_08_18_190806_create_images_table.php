<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
              //`kind` enum(\'article\', \'category\', \'tag\', \'etc\') NOT NULL COMMENT "画像種別…article:記事、category:カテゴリー、tag:タグ、etc:その他",
        DB::statement(
            'CREATE TABLE IF NOT EXISTS `images` (
              `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
              `article_id` tinyint(3) UNSIGNED DEFAULT NULL COMMENT "記事ID",
              `category_id` tinyint(3) UNSIGNED DEFAULT NULL COMMENT "カテゴリーID",
              `tag_id` tinyint(3) UNSIGNED DEFAULT NULL COMMENT "タグID",
              `writer_id` bigint(11) UNSIGNED DEFAULT NULL COMMENT "ライターID",
              `filepath` varchar(255) NOT NULL COMMENT "ファイルパス",
              `alt` varchar(100) DEFAULT NULL COMMENT "代替テキスト（altタグ）",
              `caption` varchar(100) DEFAULT NULL COMMENT "画像キャプション",
              `width` smallint(4) UNSIGNED DEFAULT 0 COMMENT "画像サイズwidth",
              `height` smallint(4) UNSIGNED DEFAULT 0 COMMENT "画像サイズheight",
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
        Schema::drop('images');
    }
}
