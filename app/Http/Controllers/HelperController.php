<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Collection;
use App\Http\Controllers\Controller; 
use App\Models\CommentHelper;
use App\Models\UserHelper;
use App\Models\User;
use DB;
class HelperController extends Controller
{
    public function index()
    {
        $user=Auth::user();
    if($user->role_as=="2")  //User
    {
      
      return  DB::table('user_helper')
        ->join('users','user_helper.user_id','=','users.id')
        ->where('status','0')
        ->select('user_helper.id','user_helper.user_id','user_helper.content','users.fullname','users.email')
        ->get();
    }
    if($user->role_as=="3")  //Post
    {
      return   DB::table('post_helper')
        ->join('post','post_helper.post_id','=','post.id')
        ->where('status','0')
        ->select('post_helper.id','post_helper.post_id','post_helper.content','post.content','post.photo')
        ->get();
    }
    if($user->role_as=="4")  //Comment
    {
        return DB::table('comment_helper')
        ->join('comments','comment_helper.comment_id','=','comments.id')
        ->where('status','0')
        ->select('comment_helper.id','comment_helper.comment_id','comment_helper.content','comments.comment')
        ->get();
    }
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
  public function AcceptUser($id)
  {
    DB::table('user_helper')->where('id',$id)->update(['status'=>'1']);
  }
  public function AcceptComment($id)
  {
    DB::table('comment_helper')->where('id',$id)->update(['status'=>'1']);
  }
  public function AcceptPost($id)
  {
    DB::table('post_helper')->where('id',$id)->update(['status'=>'1']);
  }
    /*
        $Complaints=UserHelper::All();
      //  return $Complaints;
        $user=User::where('id',$Complaints->user_id)->get('fullname','email');
        return $user;
        $other=User::where('id',$Complaints->$other_id)->get('fullname','email');
        $content=$Complaints->Content;
        $response[0]=$user;
        $response[1]=$other;
        $response[2]=$content;

        return $response;
        */

}
