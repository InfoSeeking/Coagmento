<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveKeyCodeFromKeystrokesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('keystrokes', function (Blueprint $table) {
          $table->dropColumn('key_code');
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
          $table->integer('key_code');
        });
    }
}
