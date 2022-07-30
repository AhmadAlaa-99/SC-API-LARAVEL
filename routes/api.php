<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\EventController;
//QUERY
//Route::get('query',[QueryController::class,'query']);
// Auth 
Route::post('register',[AuthController::class,'register']); //notify email
Route::post('activate',[AuthController::class,'ActivateEmail']);  //with notify dtabase broadcast
Route::post('login',[AuthController::class,'login'])->name('login'); 
Route::post('forgotpasswordCreate', [AuthController::class, 'forgotPasswordCreate']);//notify email 
Route::post('forgotpassword', [AuthController::class, 'forgotPasswordToken']);  //request code

Route::middleware('auth:api')->group(function()  //,'verified'
{
    //inf user
    Route::get('user',[AuthController::class,'showinf']);
    Route::post('setUpProfile',[AuthController::class,'setUpProfile']);
    //Friends  
    Route::post('SendRequestFriend/{id}',[UserController::class,'SendRequest']);  //with notify dtabase broadcast
    Route::post('checkstatusfriend/{id}',[UserController::class,'checkstatusfriend']);   
    Route::post('removefriend/{id}',[UserController::class,'removefriend']); //same block
    Route::get('friendsRequestsListReceive',[UserController::class,'friendsrequestsReceive']); 
    Route::get('friendsRequestsListSend',[UserController::class,'friendsrequestsSend']);
    Route::post('FriendRequestAccept/{id}',[UserController::class,'FriendRequestAccept']); //with notify dtabase broadcast
    Route::post('FriendRequestRefuse/{id}',[UserController::class,'FriendRequestRefuse']);
    Route::get('myfriends',[UserController::class,'myfriends']);
    Route::get('Userfriend/{id}',[UserController::class,'friends']);

//Messanger
//send message to UserID 
Route::post('messages', [MessagesController::class, 'store']);
//GET ALL MESSAGE FOR ConversationID
Route::get('conversations/{id}/messages', [MessagesController::class, 'index']);
// delete message 
Route::post('deletemessages/{id}', [MessagesController::class, 'destroy']);
//GET List MyConversation whith last message for every conversation 
Route::get('conversations', [ConversationsController::class, 'index']);
//Get ConversationID
Route::post('conversations/{id}', [ConversationsController::class, 'show']);
//update conversation as read
Route::post('conversations/{id}/read', [ConversationsController::class, 'markAsRead']);
//Delete Conversation
Route::post('deleteconv/{id}', [ConversationsController::class, 'destroy']);
//show friend online


   //POST
    /* get all post with count likes and comments for post */
  //  Route::get('posts',[PostController::class,'index']);
    /* add new post  */ /*notify addpost for friends*/
   Route::post('newpost',[PostController::class,'store']);


     //page : new feed get all post friends with myposts with count likes and comments for post just (order date)
   Route::get('AllPost',[PostController::class,'AllPost']);
     /* get post information by id with count likes and comments for post and content*/
    Route::get('getpostid/{id}',[PostController::class,'show']);
    


     /* delete post by id if only the user is the owner  */
    Route::delete('deletepost/{id}',[PostController::class,'destroy']);
     /* update post by id if only the user is the owner  */
    Route::post('updatepost/{id}',[PostController::class,'update']);
    /* get all post for specified owner with count likes and comments for post  */
    Route::get('getpostOwner/{id}',[PostController::class,'userpost']);
    /* search post by category */
    Route::get('searchByCategory',[PostController::class,'searchPostByCategory']);
    /* add comment with notify */ 
    Route::post('addcomment/{id}',[CommentController::class,'store']);
    /* delete comment */
    Route::delete('deletecomment/{id}',[CommentController::class,'destroy']);
    /* show comment post */
    Route::post('postComments/{id}',[CommentController::class,'postComments']);
    /* show ownerLikes post */
    Route::post('postLikes/{id}',[PostController::class,'postLikes']);
    /* update comment */
    Route::post('updatecomment/{id}',[CommentController::class,'update']);

    
    /* Like post with notify*/
    Route::post('post/{id}/like',[PostController::class,'Like']);
    /* DisLike post */
    Route::delete('post/{id}/dislike',[PostController::class,'DisLike']);


    //GROUPS
    Route::post('createGroup',[GroupController::class,'create']);
    Route::post('createPostGroup/{group_id}',[GroupController::class,'createpost']);  //like ,comment same post
    Route::get('showRequestPost/{group_id}',[GroupController::class,'showRequestPost']);
    Route::post('AcceptRequestPost/{group_id}/{post_id}',[GroupController::class,'AcceptRequestPost']);
    Route::post('RefuseRequestPost/{group_id}/{post_id}',[GroupController::class,'RefuseRequestPost']);
    Route::post('sendRequestJoin/{grop_id}',[GroupController::class,'requestJoin']);  //request join 
    Route::get('showRequestJoin/{group_id}',[GroupController::class,'showRequestsJoin']);
    Route::post('AcceptRequestJoin/{group_id}/{user_id}',[GroupController::class,'AcceptRequestJoin']);
    Route::post('RefuseRequestJoin/{group_id}/{user_id}',[GroupController::class,'RefuseRequestJoin']);
    Route::post('OuterGroup/{group_id}',[GroupController::class,'OuterGroup']);
    Route::post('deletePostGroup/{group_id}/{user_id}',[GroupController::class,'deletePostGroup']);
    Route::post('dimissalGroup/{group_id}/{user_id}',[GroupController::class,'dimissalGroup']);
    Route::post('deleteMyGroup/{group_id}',[GroupController::class,'deleteMyGroup']);
    Route::get('showOwnerGroup',[GroupController::class,'showOwnerGroup']);

    Route::get('joinedGroup',[GroupController::class,'joinedGroup']);
    Route::get('requestedGroup',[GroupController::class,'requestedGroup']);
    Route::get('proposedGroup',[GroupController::class,'proposedGroup']);
    Route::get('SearchGroup',[GroupController::class,'SearchGroup']);
    Route::get('MyPostsGroup/{group_id}',[GroupController::class,'MyPostsGroup']);
    Route::get('PostMemberGroup/{user_id}/{group_id}',[GroupController::class,'PostMemberGroup']);



//EVENTS 
Route::post('newEvent',[EventController::class,'store']);

//Notification
  
//show all notifaction 
Route::get('allnotify',[NotificationController::class,'showAllNotify']);
//show All UnreadNotify
Route::post('unreadnotify',[NotificationController::class,'showAllUnreadNotify']);
//make notification read
Route::post('makenotifyread',[NotificationController::class,'markAsReadNotify']);


//Profile
Route::get('viewprofile/{id}',[UserController::class,'viewProfile'])->name('viewprofile');  //user or friend acc id
Route::get('viewMyProfile',[UserController::class,'viewMyProfile']);  


//setting
//reset password 
Route::post('resetPassword', [AuthController::class, 'resetPassword']); //require OldPassword
//reset email
Route::post('resetEmail', [AuthController::class, 'resetEmail']);  //require password and activate newEmail 
Route::post('logout',[AuthController::class,'logout']);
});

