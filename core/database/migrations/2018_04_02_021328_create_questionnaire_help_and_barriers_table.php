<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireHelpAndBarriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_help_and_barriers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('stage_id');
            $table->integer('segment_id');
            $table->text('barriers');
            $table->text('help');
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
        Schema::drop('questionnaire_help_and_barriers');
    }
}
