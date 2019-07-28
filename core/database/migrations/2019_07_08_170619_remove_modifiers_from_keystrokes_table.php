<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveModifiersFromKeystrokesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('keystrokes', function (Blueprint $table) {
          $table->dropColumn('modifiers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('keystrokes', function (Blueprint $table) {
          $table->integer('modifiers');
        });
    }
}
