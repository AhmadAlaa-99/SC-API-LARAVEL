<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserHelper extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("user_helper", function ($table) {
            $table->id();
        $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
      //  $table->foreignId('other_id')->references('id')->on('users')->onDelete('cascade');
        $table->string('content');
        $table->enum('status',['0','1','2'])->default('0');
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
        //
    }
}
