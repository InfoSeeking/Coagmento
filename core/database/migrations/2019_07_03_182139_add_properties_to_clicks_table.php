<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertiesToClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clicks', function (Blueprint $table) {
          // $table->integer('button')->nullable();
          // $table->integer('buttons')->nullable();
          // $table->integer('detail')->nullable();

          $table->boolean('altKey')->nullable();
          $table->boolean('metaKey')->nullable();
          $table->boolean('ctrlKey')->nullable();

          // $table->boolean('modifierState')->nullable();
          // $table->string('fromElement')->nullable();
          // $table->string('toElement')->nullable();
          // $table->string('relatedTarget')->nullable();
          // $table->string('sourceCapabilities')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clicks', function (Blueprint $table) {
          // $table->dropColumn('button');
          // $table->dropColumn('buttons');

          $table->dropColumn('altKey');
          $table->dropColumn('metaKey');
          $table->dropColumn('ctrlKey');
          // $table->dropColumn('modifierState');
          // $table->dropColumn('fromElement');
          // $table->dropColumn('toElement');
          // $table->dropColumn('relatedTarget');
          // $table->dropColumn('sourceCapabilities');
        });
    }
}
