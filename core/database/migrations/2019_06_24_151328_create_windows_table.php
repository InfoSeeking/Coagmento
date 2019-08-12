<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWindowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('windows', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('top')->nullable();
            $table->integer('left')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('tabs')->nullable();
            $table->boolean('incognito')->nullable();
            $table->boolean('focused')->nullable();
            $table->boolean('alwaysOnTop')->nullable();
            $table->string('type')->nullable();
            $table->string('state')->nullable();
            $table->string('sessionId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('windows');
    }
}
