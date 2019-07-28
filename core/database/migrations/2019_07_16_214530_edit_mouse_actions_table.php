<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditMouseActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $table->dropColumn('altKey');
      $table->dropColumn('metaKey');
      $table->dropColumn('ctrlKey');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      $table->boolean('altKey')->nullable();
      $table->boolean('metaKey')->nullable();
      $table->boolean('ctrlKey')->nullable();
    }
}
