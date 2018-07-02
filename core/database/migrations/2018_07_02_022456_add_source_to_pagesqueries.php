<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceToPagesqueries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function(Blueprint $table){
            $table->text('source')->nullable();
        });

        Schema::table('queries', function(Blueprint $table){
            $table->text('source')->nullable();
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
            $table->dropColumn('source');
        });

        Schema::table('queries', function(Blueprint $table){
            $table->dropColumn('source');
        });
    }
}
