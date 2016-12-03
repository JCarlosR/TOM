<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->increments('id');

            // associated fan page
            $table->integer('fan_page_id')->unsigned();
            $table->foreign('fan_page_id')->references('id')->on('fan_pages');

            // data
            $table->string('description');
            $table->date('end_date')->nullable();
            $table->string('image'); // extension: .JPG, .PNG
            $table->smallInteger('attempts'); // attempts: frequency to win

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
        Schema::drop('promotions');
    }
}
