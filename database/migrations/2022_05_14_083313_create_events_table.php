<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('Content');
            $table->string('Category');
            $table->string('photo');
            $table->integer('month');
            $table->integer('day');
            $table->integer('year');
            $table->tinyInteger('status')->default('0');
            $table->string('time')->default('0');
            $table->string('share')->default('0');
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
        Schema::dropIfExists('events');
    }
}
