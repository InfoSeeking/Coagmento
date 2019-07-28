<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('tabid');
            $table->integer('index');
            $table->integer('windowId');
            $table->integer('openerTabId')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->double('zoomFactor')->nullable();
            $table->boolean('highlighted')->nullable();
            $table->boolean('active')->nullable();
            $table->boolean('pinned')->nullable();
            $table->boolean('audible')->nullable();
            $table->boolean('discarded')->nullable();
            $table->boolean('autoDiscardable')->nullable();
            $table->boolean('incognito')->nullable();
            $table->boolean('selected')->nullable();
            $table->string('url')->nullable();
            $table->string('title')->nullable();
            $table->string('status')->nullable();
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
        Schema::drop('tabs');
    }
}
