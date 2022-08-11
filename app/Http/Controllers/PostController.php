<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Friend;
use App\Models\Like;
use App\Notifications\FriendsPost;
use App\Notifications\LikePostNotify;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Carbon; 
use App\Http\Controller\UserController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DateTime;
use DB;

class PostController extends BaseController
{
    ##################################################################
    /* get all post for user id  */
    public function userPost($id)
    {
        $userID=Auth::user()->id;
        $friends_id1=Friend::select('user_id')->where(['user_id'=>$id,'friend_id'=>$userID,'accept'=>1])->count();
        $friends_id2=Friend::select('friend_id')->where(['friend_id'=>$id,'user_id'=>$userID,'accept'=>1])->count();
        if ($friends_id1>0|| $friends_id2>0 )
        {        
        $Post = Post::withCount('comments','likes')->where('user_id', $id)->get();
        return $this->sendResponse($Post, 'Post UserId');
        }
        else
        {
            return $this->sendError('OWNERPOSTS NOT FRIEND');
        }
    }
    ##################################################################
    /* get all post friends with my post in page newFeed(Order Date) */
    public function AllPost()
    {
        /*
        $user_id=Auth::user()->id;
        $friends_id1=array();
        $friends_id1=Friend::where(['friend_id'=>$user_id,'accept'=>1])->get();
        $friends_id1=array_flatten(json_decode(json_encode($friends_id1),true));
        $friends_id2=array();
        $friends_id2=Friend::where(['user_id'=>$user_id,'accept'=>1])->get();
        $friends_id2=array_flatten(json_decode(json_encode($friends_id2),true));
        $friends_ids=array();
        $friends_ids=array_merge($friends_id1,$friends_id2);
      
        $friendsPost=Post::withCount('comments','likes')->whereIn('user_id', $friends_ids)->orderBy('id','Desc')->get();
       */
        $post=Post::withCount('comments','likes')->orderBy('created_at','desc')->paginate('8');
        return $this->sendResponse($post, 'Post My Friends');  
     }

    /* add new post  */
    public function store(Request $request)
    {
        //return 'h';
        $input = $request->all();
        $validator = Validator::make($input, [
            'Content' => 'string',
        //   'Category'=>'required',
            'photo' => 'required|image|mimes:jpg,bmp,png'
        ]);
      // return $input;
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        } 
        $user = Auth::user();
        $input['user_id'] = Auth::id();
        $image_name = time() . '.' . $request->photo->extension();
        $request->photo->move(public_path('upload/Post_images'), $image_name);
        $Post = Post::create([
            'Content' => $request->Content,
         //   'Category'=>$request->Category,
            'user_id' => $input['user_id'],
            'photo' => $image_name,

        ]);
     //    $myfriendsID=app('App\Http\Controllers\UserController')->myfriends();
         /*
        return $myfriendsID;  
        "data": [
        {
            "id": 3,
            "firstname": "Harvey",
            "profile_image": null,
            "avatar_url": "https://ui-avatars.com/api/?background=0D8ABC&color=fff&name="
        }
        */
      //  return $myfriendsID->id;
        /*
        foreach($myfriendsID as $friends)
        {
           // return $myfriendsID[0]->id;
            $myfriends=User::where('id',$friends->id)->first();
         ///   $myfriends->notify(new FriendsPost($user));
            
        }
        */

        /*
        //send notify for friends 
        $userID=Auth::user()->id;
        $friends_id1=Friend::select('user_id')->where(['friend_id'=>$userID,'accept'=>1])->get();
        $friends_id2=Friend::select('friend_id')->where(['user_id'=>$userID,'accept'=>1])->get();
        $userl=User::whereIn('id',$friends_id1)->OrWhereIn('id',$friends_id2)->first();
      //  $userf->notify(new FriendsPost($user));
        $userl->notify(new FriendsPost($user));
        */
        return $this->sendResponse($Post, 'Post added successfully');
    }
    ##################################################################
    /* get Post information by id */
    public function show($id)
    {
        /*
        $user_id=Auth::user()->id;
        $friends_id1=array();
        $friends_id1=Friend::where(['friend_id'=>$user_id,'accept'=>1])->get();
        $friends_id1=array_flatten(json_decode(json_encode($friends_id1),true));
        $friends_id2=array();
        $friends_id2=Friend::where(['user_id'=>$user_id,'accept'=>1])->get();
        $friends_id2=array_flatten(json_decode(json_encode($friends_id2),true));
        $friends=array();
        $friends=array_merge($friends_id1,$friends_id2);

        $Post= Post::with('comments','likes')->where(['id'=>$id,'user_id'=>$friends])->get();

        */
        
        $Post= Post::with([
            'comments',function($query)
            {
                $query->orderBy('created_at','desc')->take(3);
            }
        ],'likes')->withCount('likes','comment')->get();

        if (is_null($Post))
         {
            return $this->sendError('post not found');
        }
        return $this->sendResponse($Post , 'post retrieves successfully');
    }

    ##################################################################

    /* delete post by id if only the user is the owner  */
    public function destroy($id)
    {
        $errorMessage = [];
        $Post = Post::find($id);
        if ( $Post == null) {
            return $this->sendError('the post does not exist', $errorMessage);
        }
        if ($Post->user_id != Auth::id()) {
            return $this->sendError('you dont have rights', $errorMessage);
        }
        $Post->delete();
        return $this->sendResponse(true, 'post delete successfully');
    }

    ##################################################################

    /* update post by id if only the user is the owner  */
    public function update(Request $request, $id)
    {
        $errorMessage = [];
        $Post = Post ::find($id);
        $input = $request->all();
        if ($Post  == null) {
            return $this->sendError('the post does not exist', $errorMessage);
        }
        if ($Post->user_id != Auth::id()) {
            return $this->sendError('you dont have rights', $errorMessage);
        }

        $validator = Validator::make($input, [
            'Content' => 'required|String',
           // 'Category'=>'String',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

       $Post->update([
        'Content' =>$request->Content,
       // 'Category'=>$request->Category,
       ]);
        return $this->sendResponse($Post, 'post update');
    }


    /* search post by category */
    public function searchPostByCategory(Request $request)
    {
        $Post = Post::where('Category', $request->Category)->get();
        if ($Post->isEmpty()) {
            return $this->sendError('post by category not found ', 'error');
        }

        if ($Post->isNotEmpty()) {
            return $this->sendResponse($Post, 'post by category found successfully');
        }


    }
    public function Like(Request $request,$id)
    {
        $user_id=Auth::user()->id;
        $post=Post::findOrFail($id);
        $data=$request->all();
        $data['post_id']=$id;
        $data['user_id']=auth::id();
        Like::firstOrCreate($data,$data);
        $infUser=User::where('id',$user_id)->select('id','fullname','profile_image')->first();
       // return $infUser;
        $ownerID=Post::select('user_id')->where('id',$id)->first();
        $ownerPost=User::where('id',$ownerID->user_id)->first();
    //    $ownerPost->notify(new LikePostNotify($infUser,$post));
        return $this->sendResponse($infUser, 'Liked');

    }
    public function disLike($id)
    {
        $like=Like::where('post_id',$id)->where('user_id',auth()->id())->firstOrFail()->delete();
        return response()->json(['message'=>'Dislike done'],200);
    }

    public function postLikes($id)
    {
        $likes=Like::where('post_id',$id)->pluck('user_id');
        $users=User::where('id',$likes)->select('fullname','profile_image')->get();
        return $this->sendResponse($users, 'postLikes');
    }

}
