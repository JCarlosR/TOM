<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFbAccessTokenToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            // https://stackoverflow.com/a/16365828/3692788
            $table->text('fb_access_token')->nullable();
            $table->dateTime('fb_access_token_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn([
                'fb_access_token', 'fb_access_token_updated_at'
            ]);
        });
    }
}
