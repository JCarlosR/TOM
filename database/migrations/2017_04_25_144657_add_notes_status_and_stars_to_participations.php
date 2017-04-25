<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotesStatusAndStarsToParticipations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participations', function($table){
            $table->text('notes')->after('is_winner');
            $table->string('status')->after('notes');
            $table->integer('stars')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participations', function($table) {
            $table->dropColumn('notes');
            $table->dropColumn('status');
            $table->dropColumn('stars');
        });
    }
}
