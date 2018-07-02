<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHostToPagesqueries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function(Blueprint $table){
            $table->text('host')->nullable();
        });

        Schema::table('queries', function(Blueprint $table){
            $table->text('host')->nullable();
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
            $table->dropColumn('host');
        });

        Schema::table('queries', function(Blueprint $table){
            $table->dropColumn('host');
        });
    }
}
