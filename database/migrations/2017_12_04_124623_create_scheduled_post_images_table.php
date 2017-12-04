<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduledPostImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_post_images', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->increments('id');

            // owner
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('cloudinary_public_id');

            $table->integer('scheduled_post_id')->unsigned()->nullable();
            $table->string('secure_url');

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
        Schema::drop('scheduled_post_images');
    }
}
