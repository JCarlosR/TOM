<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduledPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_posts', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->increments('id');

            $table->string('type'); // Link, Image, Video

            $table->string('link')->nullable();
            $table->string('image_url')->nullable();
            $table->string('video_url')->nullable();

            $table->string('description');
            $table->string('status'); // Pending, Sent, Cancelled, Error

            $table->date('scheduled_date')->nullable();
            $table->time('scheduled_time')->nullable();

            // destination group or page id
            $table->string('fb_destination_id')->nullable();
            // author of the post
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::drop('scheduled_posts');
    }
}
