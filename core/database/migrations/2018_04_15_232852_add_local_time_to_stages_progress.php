<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocalTimeToStagesProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stages_progress', function(Blueprint $table){
            $table->timestamp('created_at_local');
            $table->bigInteger('created_at_local_ms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stages_progress', function(Blueprint $table){
            $table->dropColumn('created_at_local');
            $table->dropColumn('created_at_local_ms');
        });
    }
}
