<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeStagesProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stages_progress', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->nullable();
            $table->integer('user_id');
            $table->integer('stage_id');

            $table->date('date')->nullable();
            $table->date('local_date')->nullable();

            $table->time('time')->nullable();
            $table->time('local_time')->nullable();

            $table->bigInteger('timestamp')->nullable();
            $table->bigInteger('local_timestamp')->nullable();
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
        Schema::drop('stages_progress');
    }
}
