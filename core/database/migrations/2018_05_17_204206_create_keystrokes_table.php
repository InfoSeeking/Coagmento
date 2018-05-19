<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeystrokesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keystrokes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('project_id');
            $table->integer('stage_id');
            $table->integer('key_code');
            $table->integer('modifiers')->nullable();
            $table->timestamp('created_at_local');
            $table->bigInteger('created_at_local_ms');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('keystrokes');
    }
}
