<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPretaskQuestionnaire extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaire_pretasks', function(Blueprint $table){
            $table->integer('topic_prev_knowledge')->unsigned();
            $table->integer('goal_specific')->unsigned();
            $table->integer('task_pre_difficulty')->unsigned();
            $table->integer('narrow_information')->unsigned();
            $table->integer('task_newinformation')->unsigned();
            $table->integer('task_unspecified')->unsigned();
            $table->integer('task_detail')->unsigned();
            $table->integer('task_knowspecific')->unsigned();
            $table->integer('task_specificitems')->unsigned();
            $table->integer('task_factors')->unsigned();
            $table->integer('queries_start')->unsigned();
            $table->integer('know_usefulinfo')->unsigned();
            $table->integer('useful_notobtain')->unsigned();
            $table->dropColumn('task_knowledge');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaire_pretasks', function(Blueprint $table){
            $table->integer('task_knowledge')->unsigned();
            $table->dropColumn('topic_prev_knowledge');
            $table->dropColumn('goal_specific');
            $table->dropColumn('task_pre_difficulty');
            $table->dropColumn('narrow_information');
            $table->dropColumn('task_newinformation');
            $table->dropColumn('task_unspecified');
            $table->dropColumn('task_detail');
            $table->dropColumn('task_knowspecific');
            $table->dropColumn('task_specificitems');
            $table->dropColumn('task_factors');
            $table->dropColumn('queries_start');
            $table->dropColumn('know_usefulinfo');
            $table->dropColumn('useful_notobtain');
        });
    }
}
