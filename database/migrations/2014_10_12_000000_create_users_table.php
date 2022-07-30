<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullname')->nullable();
            $table->integer('role_as')->default(0);
            $table->bigInteger('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('gender')->nullable();
            $table->string('location')->nullable();
            $table->string('dateBirth')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('age')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
         //   $table->string('fcm_token')->nullable();
            $table->string('password');
            $table->string('c_password');
     
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
