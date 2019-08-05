<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWheelActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('wheel_actions', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('user_id');
          $table->integer('project_id');
          $table->integer('stage_id');
          $table->integer('client_x')->nullable();
          $table->integer('client_y')->nullable();
          $table->integer('page_x')->nullable();
          $table->integer('page_y')->nullable();
          $table->integer('screen_x')->nullable();
          $table->integer('screen_y')->nullable();
          $table->integer('scroll_x')->nullable();
          $table->integer('scroll_y')->nullable();
          $table->integer('layer_x')->nullable();
          $table->integer('layer_y')->nullable();
          $table->integer('movement_x')->nullable();
          $table->integer('movement_y')->nullable();
          $table->integer('offset_x')->nullable();
          $table->integer('offset_y')->nullable();
          $table->timestamp('created_at_local');
          $table->bigInteger('created_at_local_ms');
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wheel_actions');
    }
}