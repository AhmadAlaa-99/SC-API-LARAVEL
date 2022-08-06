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
        $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreignId('other_id')->references('id')->on('users')->onDelete('cascade');
        $table->string('content');
        $table->integer('enum',['0','1','2']);
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
