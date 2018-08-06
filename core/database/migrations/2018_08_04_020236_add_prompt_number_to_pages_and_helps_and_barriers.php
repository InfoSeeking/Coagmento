<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPromptNumberToPagesAndHelpsAndBarriers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->integer('prompt_number')->nullable();
        });

        Schema::table('questionnaire_help_and_barriers', function (Blueprint $table) {
            $table->integer('prompt_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaire_help_and_barriers', function (Blueprint $table) {
            $table->dropColumn('prompt_number');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('prompt_number');
        });
    }

}
