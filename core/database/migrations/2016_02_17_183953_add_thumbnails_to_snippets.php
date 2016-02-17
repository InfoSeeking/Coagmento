<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThumbnailsToSnippets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('snippets', function(Blueprint $table) {
            $table->integer('thumbnail_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('snippets', function(Blueprint $table) {
            $table->dropColumn('thumbnail_id');
        });
    }
}
