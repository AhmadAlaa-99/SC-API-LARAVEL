<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
//class User extends Authenticatable
class Helper extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    protected $table = 'helpers';
    protected $guarded=[];
}
