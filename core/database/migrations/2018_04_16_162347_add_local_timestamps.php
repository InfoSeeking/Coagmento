<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocalTimestamps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        $tables = array('bookmarks','bookmarks_and_tags','chat_messages','docs','memberships','old_mappings','pages','password_resets','projects','queries','questionnaire_help_and_barriers','questionnaire_posttasks','questionnaire_pretasks',
//            'questionnaire_tests',
//            'questionnaire2_tests',
            'snippets','stages','tags','tasks','thumbnails','users','v2_notifications');
        foreach($tables as $tablename){
            Schema::table($tablename, function(Blueprint $table){
                $table->timestamp('created_at_local');
                $table->bigInteger('created_at_local_ms');
            });
        }




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


        $tables = array('bookmarks','bookmarks_and_tags','chat_messages','docs','memberships','old_mappings','pages','password_resets','projects','queries','questionnaire_help_and_barriers','questionnaire_posttasks','questionnaire_pretasks',
//            'questionnaire_tests',
//            'questionnaire2_tests',
            'snippets','stages','tags','tasks','thumbnails','users','v2_notifications');
        foreach($tables as $tablename) {
            Schema::table($tablename, function (Blueprint $table) {
                $table->dropColumn('created_at_local');
                $table->dropColumn('created_at_local_ms');
            });
        }
    }
}
