<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemographicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demographics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->text('age');
            $table->text('gender');
            $table->text('major');
            $table->text('english_first');
            $table->text('native_language');
            $table->text('search_experience');
            $table->text('search_frequency');
            $table->text('nonsearch_frequency');
            $table->boolean('consent_datacollection');
            $table->boolean('consent_audio');
            $table->boolean('consent_furtheruse');
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
        Schema::drop('demographics');
    }
}
