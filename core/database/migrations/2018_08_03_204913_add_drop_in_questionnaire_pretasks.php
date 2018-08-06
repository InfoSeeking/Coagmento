<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDropInQuestionnairePretasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaire_pretasks', function (Blueprint $table) {
            $table->text('help')->nullable();
            $table->integer('task_familiarity')->nullable();
            $table->integer('task_effort')->nullable();

            $table->dropColumn('search_difficulty');
            $table->dropColumn('decide_usefulness');
            $table->dropColumn('information_understanding');
            $table->dropColumn('information_integration');
            $table->dropColumn('information_sufficient');
            $table->dropColumn('topic_prev_knowledge');
            $table->dropColumn('task_interest');
            $table->dropColumn('task_specificitems');
            $table->dropColumn('task_factors');
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
            $table->dropColumn('help');
            $table->dropColumn('task_familiarity');
            $table->dropColumn('task_effort');

            $table->integer('decide_usefulness')->nullable();
            $table->integer('search_difficulty')->nullable();
            $table->integer('information_understanding')->nullable();
            $table->integer('information_integration')->nullable();
            $table->integer('information_sufficient')->nullable();
            $table->integer('topic_prev_knowledge')->nullable();
            $table->integer('task_interest')->nullable();
            $table->integer('task_specificitems')->nullable();
            $table->integer('task_factors')->nullable();
        });
    }
}
