<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePretaskPosttaskColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('questionnaire_pretasks', function (Blueprint $table) {
            $table->integer('task_knowledge')->unsigned();
            $table->integer('task_interest')->unsigned();
        });


        Schema::table('questionnaire_posttasks', function (Blueprint $table) {
            $table->integer('difficulty_search')->unsigned();
            $table->integer('difficulty_understand')->unsigned();
            $table->integer('difficulty_usefulinformation')->unsigned();
            $table->integer('difficulty_integrate')->unsigned();
            $table->integer('difficulty_enoughinformation')->unsigned();
            $table->dropColumn('difficulty');
            $table->dropColumn('task_success');
            $table->dropColumn('enough_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('questionnaire_pretasks', function (Blueprint $table) {
            $table->dropColumn('task_knowledge');
            $table->dropColumn('task_interest');
        });



        Schema::table('questionnaire_posttasks', function (Blueprint $table) {
            $table->integer('difficulty')->unsigned();
            $table->integer('task_success')->unsigned();
            $table->integer('enough_time')->unsigned();
            $table->dropColumn('difficulty_search');
            $table->dropColumn('difficulty_understand');
            $table->dropColumn('difficulty_usefulinformation');
            $table->dropColumn('difficulty_integrate');
            $table->dropColumn('difficulty_enoughinformation');
        });
    }
}
