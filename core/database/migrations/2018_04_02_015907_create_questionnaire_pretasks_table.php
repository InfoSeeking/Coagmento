<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnairePretasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_pretasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('stage_id');
            $table->integer('search_difficulty');
            $table->integer('information_understanding');
            $table->integer('decide_usefulness');
            $table->integer('information_integration');
            $table->integer('information_sufficient');
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
        Schema::drop('questionnaire_pretasks');
    }
}
