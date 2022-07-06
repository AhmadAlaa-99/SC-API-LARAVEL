<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
//use App\Notifications\ActivateEmail;
use App\Notifications\RequestFriendNotification;
use App\Notifications\FriendRequestAcceptNotification;
use App\Models\User;
use App\Models\Friend;

class UserController extends BaseController
{
    public function SendRequest(Request $request,$id)
    {
        $User=User::where('id',$id)->first();
      //  $sender=User::Auth();   error failed call auth  --> Auth::user();
         $sender=Auth::user();
        if ($User)
        {
            $friend=Friend::Create(['user_id'=>Auth::user()->id,'friend_id'=>$id]);
            $User->notify(new RequestFriendNotification($sender)); 
              // error : Call to undefined method Illuminate\Database\Eloquent\Builder::notify()  solve : use get\first
            return response()->json('REQUEST SEND SUCCESSFULL',200);
        }
    }
    public function friends($id)
    { 
         //view List User Friends 
         $friend_id=$id;
         $friends_Count1=Friend::where(['friend_id'=>$friend_id,'accept'=>1])->count();
         $friend_ids1=array();
         if($friends_Count1>0)
         {
             $friend_ids1=Friend::select('user_id')->where(['friend_id'=>$friend_id,'accept'=>1])->get();
             $friend_ids1=array_flatten(json_decode(json_encode($friend_ids1),true));
         }
         $friends_Count2=Friend::where(['user_id'=>$friend_id,'accept'=>1])->count();
         $friend_ids2=array();
         if($friends_Count2>0)
         {
             $friend_ids2=Friend::select('friend_id')->where(['user_id'=>$friend_id,'accept'=>1])->get();
            $friend_ids2=array_flatten(json_decode(json_encode($friend_ids2),true));
         }
        // $merged=$friend_ids1->merge($friend_ids2);
         //$result=$merged->all();
         //return $result;

        $friends_ids=array();
        $friends_ids=array_merge($friend_ids1,$friend_ids2);  //1 5 8
        $friendsList=User::whereIn('id', $friends_ids)->select('firstname','profile_image')->orderBy('id','Desc')->get();
        //where : return just firstuser but whereIn return all user
        return $this->sendResponse($friendsList, 'List User Friends');
    }
    public function checkstatusfriend($id)
    {
            // check the user is friend or not 
            $user_id=Auth::user()->id;
            $friend_id=$id;
            $friendsCount=Friend::where(['user_id'=>$user_id,'friend_id'=>$friend_id])->count();
            if($friendsCount>0)
            {
                $friendsDetails=Friend::where(['user_id'=>$user_id,'friend_id'=>$friend_id])->first();
                if($friendsDetails->accept==1)
                {
                    $friendstatus="friends";
                }
                else
                {
                    $friendstatus="Friend Requst Sent";
                }

            }
            else
            {
                $friendsCount=Friend::where(['user_id'=>$friend_id,'friend_id'=>$user_id])->count();
                if ( $friendsCount>0)
                {
                    $friendsDetails=Friend::where(['user_id'=>$friend_id,'friend_id'=>$user_id])->first();
                    if($friendsDetails->accept==1)
                    {
                    $friendstatus="friends";
                    }
                    else
                {
                    $friendstatus="confirm friend request";
                }}
                else
                {
                $friendstatus="Add friend";
                }
            }
            
            return $friendstatus;
        }
    
    public function removefriend($id)
    {
        $userCount=User::where('id',$id)->count();
        if($userCount>0)
        {
            $user_id=Auth::user()->id;
            $friend_id=$id; // = User::getUserId($username);
            Friend::where(['user_id'=>$user_id,'friend_id'=>$friend_id,'accept'=>1])->delete();
            Friend::where(['user_id'=>$friend_id,'friend_id'=>$user_id,'accept'=>1])->delete();
            return $this->sendResponse(true, 'delete friend');
        }
    
    }
    public function friendsrequestsReceive()
    {
        $user_id=Auth::user()->id;
        $friendsrequests=Friend::where(['friend_id'=>$user_id,'accept'=>0])->get();
        return $this->sendResponse($friendsrequests, 'list friendRequset');
    }

    public function friendsrequestsSend()
    {
        $user_id=Auth::user()->id;
        $friendsrequests=Friend::where(['user_id'=>$user_id,'accept'=>0])->get();
        
        return $this->sendResponse($friendsrequests, 'list friendRequset');
    }
    public function FriendRequestAccept($sender_id)
    {
        $receiver=Auth::user();
      // return  $receiver->username;   true
        $friend= Friend::where(['user_id'=>$sender_id,'friend_id'=>$receiver->id])->update(['accept'=>1]);
        $Sender=User::where(['id'=>$sender_id])->first();
       // return $Sender;
        // User::whereId($sender_id)->notify(new FriendRequestAccept($user)); //error builder
        $Sender->notify(new FriendRequestAcceptNotification($receiver)); 
        //Call to undefined method Illuminate\Database\Eloquent\Builder::notify() --> not forget get/first with where 
        //note : with get error but first work 
        // get:     first: 
        return $this->sendResponse(true, 'done accept friend');
    }
    
     public function FriendRequestRefuse($sender_id)
      {
        $receiver_id=Auth::user()->id;
        Friend::where(['user_id'=>$sender_id,'friend_id'=>$receiver_id])->delete();
        Friend::where(['user_id'=>$receiver_id,'friend_id'=>$sender_id])->delete();
        return $this->sendResponse(true, 'refuse request friend');
      }

      public function myfriends()   // show friends accept==1
      {
        $user_id=Auth::user()->id;
        $friends_id1=array();
        $friends_id1=Friend::select('user_id')->where(['friend_id'=>$user_id,'accept'=>1])->get();
        $friends_id1=array_flatten(json_decode(json_encode($friends_id1),true));
        $friends_id2=array();
        $friends_id2=Friend::select('friend_id')->where(['user_id'=>$user_id,'accept'=>1])->get();
        $friends_id2=array_flatten(json_decode(json_encode($friends_id2),true));
        $friends_ids=array();
        $friends_ids=array_merge($friends_id1,$friends_id2);
       // return $friends_ids;
        $friendsList=User::whereIn('id', $friends_ids)->select('id','firstname','profile_image')->orderBy('id','Desc')->get();
        return $friendsList;
      }
       //profile_image/username/friends/posts with countlike comment for every post
      public function viewProfile($id)
    {
        $status=$this->checkstatusfriend($id);

        if($status="friends"||"confirm friend request")
        {
            //profile friend
            $friends=$this->friends($id);
            $Profile=User::where('id',$id)->select('id','firstname','lastname','username'
            ,'phone','city','country','email','profile_image')
            ->with([
                'posts' => function($builder) {$builder->withCount('comments','likes');},
                ])->get();    
            return response()->json(['profile' => $Profile, 'friends' =>$friends ]);
  
        }
        else if($status="Add friend"||"Friend Requst Sent")
        {
            //profile user
            $Profile=User::where('id',$id)->select('id','firstname','lastname','username'
            ,'city','country','profile_image')
            ->get();
            return response()->json(['profile' => $Profile]);

        }

    }

    public function viewMyProfile()
    {
        $myfriends=$this->myfriends();

        $user=Auth::User()->id;
        $MyProfile=User::where('id',$user)->select('id','firstname','lastname','username'
        ,'phone','city','country','email','profile_image')
        ->with([
            'posts' => function($builder) {$builder->withCount('comments','likes');},
            ])->get();    

        return response()->json(['my_profile' => $MyProfile, 'myfriends' =>$myfriends ]);
}
}
