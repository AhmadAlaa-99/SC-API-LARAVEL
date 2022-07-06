<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
          //  $table->bigInteger('owner_id'); //error 
            $table->foreignId('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('category');
            $table->string('photo');
            $table->string('description');
         //   $table->primary(['name','owner_id','id']);
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
        Schema::dropIfExists('groups');
    }
}
