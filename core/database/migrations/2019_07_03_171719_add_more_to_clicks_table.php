<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreToClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clicks', function (Blueprint $table) {
          $table->integer('layer_x')->nullable();
          $table->integer('layer_y')->nullable();
          $table->integer('movement_x')->nullable();
          $table->integer('movement_y')->nullable();
          $table->integer('offset_x')->nullable();
          $table->integer('offset_y')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clicks', function (Blueprint $table) {
          $table->dropColumn('layer_x');
          $table->dropColumn('layer_y');
          $table->dropColumn('movement_x');
          $table->dropColumn('movement_y');
          $table->dropColumn('offset_x');
          $table->dropColumn('offset_y');
        });
    }
}
