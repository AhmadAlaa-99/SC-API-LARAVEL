<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PostGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_group', function (Blueprint $table) {
        $table->id(); 
        $table->foreignId('group_id')->references('id')->on('groups')->onDelete('cascade');
        $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->string('content');
        $table->string('category');
        $table->tinyInteger('accept')->default('0');
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
