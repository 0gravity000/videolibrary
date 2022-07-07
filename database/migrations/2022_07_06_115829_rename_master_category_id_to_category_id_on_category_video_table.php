<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameMasterCategoryIdToCategoryIdOnCategoryVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_video', function (Blueprint $table) {
            $table->renameColumn('master_category_id', 'category_id');
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
            $table->renameColumn('category_id', 'master_category_id');
        });
    }
}
