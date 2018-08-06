<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDropInQuestionnairePosttasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaire_posttasks', function (Blueprint $table) {
            $table->text('future_help')->nullable();
            $table->integer('task_difficult')->nullable();

            $table->dropColumn('difficulty_search');
            $table->dropColumn('difficulty_understand');
            $table->dropColumn('difficulty_usefulinformation');
            $table->dropColumn('difficulty_integrate');
            $table->dropColumn('difficulty_enoughinformation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaire_posttasks', function (Blueprint $table) {
            $table->dropColumn('future_help');
            $table->dropColumn('task_difficult');

            $table->integer('difficulty_search')->nullable();
            $table->integer('difficulty_understand')->nullable();
            $table->integer('difficulty_usefulinformation')->nullable();
            $table->integer('difficulty_integrate')->nullable();
            $table->integer('difficulty_enoughinformation')->nullable();
        });
    }
}
