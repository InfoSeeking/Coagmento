<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertiesToMouseActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mouse_actions', function (Blueprint $table) {
          $table->integer('layer_x')->nullable();
          $table->integer('layer_y')->nullable();
          $table->integer('offset_x')->nullable();
          $table->integer('offset_y')->nullable();
          $table->boolean('altKey')->nullable();
          $table->boolean('metaKey')->nullable();
          $table->boolean('ctrlKey')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mouse_actions', function (Blueprint $table) {
          $table->dropColumn('layer_x');
          $table->dropColumn('layer_y');
          $table->dropColumn('offset_x');
          $table->dropColumn('offset_y');
          $table->dropColumn('altKey');
          $table->dropColumn('metaKey');
          $table->dropColumn('ctrlKey');
        });
    }
}
