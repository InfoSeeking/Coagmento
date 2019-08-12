<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderToBarriersAndHelps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaire_help_and_barriers', function (Blueprint $table) {
            $table->text('barriers_order')->nullable();
            $table->text('help_order')->nullable();
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
            $table->dropColumn('barriers_order');
            $table->dropColumn('help_order');
        });
    }
}
