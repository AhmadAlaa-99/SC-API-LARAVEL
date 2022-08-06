<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
   // protected $fillable=['Content','Category'];
    protected $guarded=[];
    protected $table="Post";
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }
    public function likes()
    {
        return $this->hasMany('App\Models\Like');
    }
    public function latestComments()
    {
        return $this->comments()->latest()->nPerGroup('post_id', 3);
    }
}
