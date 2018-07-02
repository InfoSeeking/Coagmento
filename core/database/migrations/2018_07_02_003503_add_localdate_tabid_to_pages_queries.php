<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocaldateTabidToPagesQueries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function(Blueprint $table){
            $table->timestamp('date_local');
            $table->integer('tab_id')->nullable();
        });

        Schema::table('queries', function(Blueprint $table){
            $table->timestamp('date_local');
            $table->integer('tab_id')->nullable();
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
            $table->dropColumn('date_local');
            $table->dropColumn('tab_id');
        });

        Schema::table('queries', function(Blueprint $table){
            $table->dropColumn('date_local');
            $table->dropColumn('tab_id');
        });
    }
}
