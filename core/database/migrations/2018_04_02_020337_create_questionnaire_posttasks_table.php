<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnairePosttasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_posttasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('stage_id');
            $table->integer('satisfaction')->unsigned();
            $table->integer('system_helpfulness')->unsigned();
            $table->integer('goal_success')->unsigned();
            $table->integer('mental_demand')->unsigned();
            $table->integer('physical_demand')->unsigned();
            $table->integer('temporal_demand')->unsigned();
            $table->integer('effort')->unsigned();
            $table->integer('frustration')->unsigned();
            $table->integer('difficulty')->unsigned();
            $table->integer('task_success')->unsigned();
            $table->integer('enough_time')->unsigned();
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
        Schema::drop('questionnaire_posttasks');
    }
}
