<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditWheelActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wheel_actions', function (Blueprint $table) {
          $table->integer('delta_x')->nullable();
          $table->integer('delta_y')->nullable();
          $table->integer('delta_z')->nullable();
          $table->integer('delta_mode')->nullable();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wheel_actions', function (Blueprint $table) {
          $table->dropColumn('delta_x');
          $table->dropColumn('delta_y');
          $table->dropColumn('delta_z');
          $table->dropColumn('delta_mode');

          
        });
    }
}
