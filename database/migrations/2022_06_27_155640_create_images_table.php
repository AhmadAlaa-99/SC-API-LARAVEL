<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            //post,event,post_group
        $table->foreignId('post_id')->references('id')->on('post')->onDelete('cascade');
        $table->foreignId('post_group_id')->references('id')->on('post_group')->onDelete('cascade');
        $table->foreignId('event_id')->references('id')->on('events')->onDelete('cascade');
      //  $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');


                $table->string('path')->nullable();
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
        Schema::dropIfExists('images');
    }
}
