<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertiesToKeystrokesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('keystrokes', function (Blueprint $table) {
          $table->string('code');
          $table->integer('which');
          $table->string('modifier')->nullable();
          $table->boolean('repeat');
          $table->char('key');
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
          $table->dropColumn('code');
          $table->dropColumn('which');
          $table->dropColumn('modifier');
          $table->dropColumn('repeat');
          $table->dropColumn('key');
        });
    }
}
