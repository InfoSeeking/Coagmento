<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('questionnaire_id');
            $table->integer('user_id');
            $table->string('type');
            $table->boolean('required')/*->nullable()*/;
            $table->boolean('inline')/*->nullable()*/;
            $table->string('description')->nullable();
            $table->string('label')->nullable();
            $table->string('name')->nullable();
            $table->string('subType')->nullable();
            $table->string('style')->nullable();
            $table->string('values')->nullable();
            $table->string('className')->nullable();
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
        Schema::drop('questions');
    }
}
