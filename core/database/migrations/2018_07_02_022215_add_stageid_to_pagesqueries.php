<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStageidToPagesqueries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function(Blueprint $table){
            $table->integer('stage_id')->nullable();
        });

        Schema::table('queries', function(Blueprint $table){
            $table->integer('stage_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function(Blueprint $table){
            $table->dropColumn('stage_id');
        });

        Schema::table('queries', function(Blueprint $table){
            $table->dropColumn('stage_id');
        });
    }
}
