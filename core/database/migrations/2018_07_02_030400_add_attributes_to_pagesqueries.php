<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttributesToPagesqueries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function(Blueprint $table){
            $table->text('query')->nullable();
            $table->boolean('trash')->nullable();
            $table->boolean('permanently_delete')->nullable();
            $table->boolean('active_tab')->nullable();
            $table->integer('window_id')->nullable();
            $table->boolean('is_coagmento')->nullable();
            $table->text('details')->nullable();
            $table->integer('query_segment_id')->nullable();
            $table->integer('query_segment_id_automatic')->nullable();
        });

        Schema::table('queries', function(Blueprint $table){
            $table->text('query')->nullable();
            $table->boolean('trash')->nullable();
            $table->boolean('permanently_delete')->nullable();
            $table->boolean('active_tab')->nullable();
            $table->integer('window_id')->nullable();
            $table->boolean('is_coagmento')->nullable();
            $table->text('details')->nullable();
            $table->integer('query_segment_id')->nullable();
            $table->integer('query_segment_id_automatic')->nullable();
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
            $table->dropColumn('query');
            $table->dropColumn('trash');
            $table->dropColumn('permanently_delete');
            $table->dropColumn('active_tab');
            $table->dropColumn('window_id');
            $table->dropColumn('is_coagmento');
            $table->dropColumn('details');
            $table->dropColumn('query_segment_id');
            $table->dropColumn('query_segment_id_automatic');
        });

        Schema::table('queries', function(Blueprint $table){
            $table->dropColumn('query');
            $table->dropColumn('trash');
            $table->dropColumn('permanently_delete');
            $table->dropColumn('active_tab');
            $table->dropColumn('window_id');
            $table->dropColumn('is_coagmento');
            $table->dropColumn('details');
            $table->dropColumn('query_segment_id');
            $table->dropColumn('query_segment_id_automatic');
        });
    }
}
