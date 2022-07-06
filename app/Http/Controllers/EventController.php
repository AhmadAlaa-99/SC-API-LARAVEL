<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NotifyEvent;
use DateTime;

class EventController extends BaseController
{
    /* GET ALL MyEvent  */
    public function index()
    {
     $user = Auth::user()->id;
    $Post = event::where('user_id',$user)->get();
    return $this->sendResponse($event, 'all my event');

    }

    ##################################################################

    /* get all event for user id (friend just)  */
    public function userevent($id)
    {
        $event = Event::where(['user_id'=>$id,'status'=>1,'share'=>1])->get();
        return $this->sendResponse($event, 'Event UserId');
    }

    ##################################################################

    /* add new Event */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'Content' => 'required',
            'Category'=>'required',
            'photo' => 'required|image|mimes:jpg,bmp,png',
           // 'event_at'=>'required|date_format:m/d/y'
            'month'=>'required',
            'day'=>'required',
            'year'=>'required'
        ]);
      // return $input;
        if ($validator->fails()) {
            return $this->sendError('validate Error', $validator->errors());
        }
        $user = Auth::user();
        $input['user_id'] = Auth::id();
        $image_name = time() . '.' . $request->photo->extension();
        $request->photo->move(public_path('upload/Post_images'), $image_name);

        $event=Event::create([
            'Content' => $request->Content,
            'Category'=>$request->Category,
            'user_id' => $input['user_id'],
            'photo' => $image_name,
            'month'=>$request->month,
            'day'=>$request->day,
            'year'=>$request->year

        ]);
            return $this->sendResponse($event, 'Event added successfully');
    }
    //notify friends myEvent ()
    public function NotifyEvent($event_id)
    {
        $event=Event::where('id',$event_id);
        $event->update('share','1');
         $user=Auth::id();
         $myfriendsID=app('App\Http\Controllers\UserController')->myfriends();
         foreach($myfriendsID as $friends)
        {
            $myfriends=User::where('id',$friends->id)->first();
            $myfriends->notify(new NotifyEvent($event,$user));    
        }
    }
    ##################################################################
    //get myEvent out
    public function showMyEventOUT()
    {
        $user_id=Auth::user();
        $Event = Event::all()->where(['status'=>0,'user_id'=>$user_id->id]);
        return $this->sendResponse($Event, 'SHOW ALL Event out');
    }
    //get myEvent open
    public function showMyEventOPEN()
    {
        $user_id=Auth::user();
        $Event = Event::all()->where(['status'=>1,'user_id'=>$user_id->id]);
        return $this->sendResponse($Event, 'SHOW ALL Event poen');
    }

    //get Event AllFriends (just open)
    public function showEventfriends()
    {
        $user_id=Auth::user();
        $friends=Friend::where(['friend_id'=>$user_id,'accept'=>1])->get(); // 'user_id'=>$user_id
        $Event = Event::all()->where(['status'=>1,'user_id'=>$friends->id,'share'=>1]);
        return $this->sendResponse($Event, 'SHOW ALL Event');
    }
    ##################################################################

    /* delete post by id if only the user is the owner  */
    public function destroy($id)
    {
        $errorMessage = [];
        $event = Event::find($id);
        if ( $event == null) {
            return $this->sendError('the event does not exist', $errorMessage);
        }
        if ($event->user_id != Auth::id()) {
            return $this->sendError('you dont have rights', $errorMessage);
        }
        $event->delete();
        return $this->sendResponse(true, 'event delete successfully');
    }

    ##################################################################

}
