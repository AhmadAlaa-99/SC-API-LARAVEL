<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostHelper extends Model
{
    use HasFactory;
    protected $table = 'post_helper';
    protected $guarded=[];
    public function posts()
    {

    }
    public function users()
    {
        
    }
}
