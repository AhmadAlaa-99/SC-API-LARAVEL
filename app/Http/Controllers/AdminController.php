<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
      
      $User_Helper= DB::table('user_helper')
      ->join('users','user_helper.user_id','=','users.id')
      ->where('status','1')
      ->select('user_helper.id','user_helper.user_id','user_helper.content','users.fullname','users.email')
      ->get();
       $Post_Helper=DB::table('post_helper')
      ->join('post','post_helper.post_id','=','post.id')
      ->where('status','1')
      ->select('post_helper.id','post_helper.post_id','post_helper.content','post.content','post.photo')
      ->get();
  
       $Comment_Helper=DB::table('comment_helper')
      ->join('comments','comment_helper.comment_id','=','comments.id')
      ->where('status','1')
      ->select('comment_helper.id','comment_helper.comment_id','comment_helper.content','comments.comment')      ->get();
      return [
        'UserHelper'=>$User_Helper,
        'PostHelper'=>$Post_Helper,
        'CommentHelper'=> $Comment_Helper,
      ];
    }
  public function IgnoreUser($id)      //delete
  {
        DB::table('user_helper')->where('id',$id)->delete();
  }
 public function IgnoreComment($id)
  {
    DB::table('comment_helper')->where('id',$id)->delete();
  }
  public function IgnorePost($id)
  {
     DB::table('post_helper')->where('id',$id)->delete();
  }


  public function DeleteUser($id)
  {
      $user_id=  DB::table('user_helper')->where('id',$id)->pluck('user_id');
      DB::table('users')->where('id',$user_id)->delete();
      DB::table('user_helper')->where('id',$id)->delete();
  }
  public function unActiveUser($id)
  {
    $user_id=  DB::table('user_helper')->where('id',$id)->pluck('user_id');
      DB::table('users')->where('id',$user_id)->delete();

  }
  public function DeleteComment($id)
  {
    $comment_id=  DB::table('comment_helper')->where('id',$id)->pluck('comment_id');
      DB::table('comments')->where('id',$comment_id)->delete();
      DB::table('comment_helper')->where('id',$id)->delete();
  }
  public function DeletePost($id)
  {
    $post_id=DB::table('post_helper')->where('id',$id)->pluck('post_id');
      DB::table('post')->where('id',$post_id)->delete();
      DB::table('post_helper')->where('id',$id)->delete();
  }

  public function Helpers()
  {
     return  DB::table('users')
     ->where('role_as','=','2')
     ->orWhere('role_as','=','3')
     ->orWhere('role_as','=','4')
     ->select('fullname','email')
     ->get();
  }
  public function EditHelper(Request $request,$id)
  {
    return  DB::table('users')->where('id',$id)
    ->update(
      [
        'fullname'=>$request->fullname,
        'email'=>$request->email,
    ]);
  }
}
