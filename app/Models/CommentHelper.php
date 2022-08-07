<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentHelper extends Model
{
    use HasFactory;
    protected $table = 'comment_helper';
    protected $guarded=[];
    public function comments()
    {

    }
    public function users()
    {
        
    }
}
