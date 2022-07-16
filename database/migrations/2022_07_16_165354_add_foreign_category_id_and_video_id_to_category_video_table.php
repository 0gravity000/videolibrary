<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignCategoryIdAndVideoIdToCategoryVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_video', function (Blueprint $table) {
            // デフォルトの主キーを削除
            $table->dropColumn('id');
            // 複合主キーを定義
            $table->primary(['category_id', 'video_id']);
            // 指定したカラムに外部キー制約を定義
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_video', function (Blueprint $table) {
            // --- ↓要検証 -----------------
            // 複合主キーを削除
            $table->dropPrimary('category_id_primary');
            $table->dropPrimary('video_id_primary');
            // デフォルトの主キーidを追加
            $table->bigIncrements('id');
            // 主キーを追加(デフォルトの主キー)
            $table->primary('id');
            // //--- ↑要検証 -----------------

            // 指定したカラムの外部キー制約を削除
            $table->dropForeign('category_video_category_id_foreign');
            $table->dropForeign('category_video_video_id_foreign');
        });
    }
}
