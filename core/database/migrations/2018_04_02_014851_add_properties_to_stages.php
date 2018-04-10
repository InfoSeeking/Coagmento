<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertiesToStages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stages', function(Blueprint $table){
            $table->boolean('timed')->default(false);
            $table->boolean('capture')->default(false);
            $table->boolean('display_tools')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stages', function(Blueprint $table){
            $table->dropColumn('timed');
            $table->dropColumn('capture');
            $table->dropColumn('display_tools');
        });
    }
}
