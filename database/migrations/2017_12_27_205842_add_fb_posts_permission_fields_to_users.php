<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFbPostsPermissionFieldsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table){
            $table->boolean('entered_password_for_fb_posts')->default(false);
            $table->boolean('fb_posts_revoked_permission')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('entered_password_for_fb_posts');
            $table->dropColumn('fb_posts_revoked_permission');
        });
    }
}
