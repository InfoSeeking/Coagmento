<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SnippetAddTitle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('snippets', function($table) {
            $table->string('title')->nullable();
        });
        Schema::table('bookmarks', function($table) {
            $table->string('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('snippets', function($table) {
            $table->dropColumn('title');
        });
        Schema::table('bookmarks', function($table) {
            $table->dropColumn('notes');
        });
    }
}
