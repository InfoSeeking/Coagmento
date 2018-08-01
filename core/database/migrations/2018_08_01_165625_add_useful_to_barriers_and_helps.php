<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsefulToBarriersAndHelps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaire_help_and_barriers', function (Blueprint $table) {
            $table->integer('useful');
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
            $table->dropColumn('useful');
        });
    }
}