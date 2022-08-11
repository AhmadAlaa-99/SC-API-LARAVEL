<?php

namespace App\Http\Controllers;

use App\Events\MessageCreated;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Recipient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\http\Controllers\BaseController as BaseController;
use App\http\Controllers\UserController as UserController;
use Throwable;

class MessagesController extends BaseController
{
   // use UserController;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //get messages for conversationID
    public function index($id)
    {
        $user = Auth::user();
        $conversation = $user->conversations()   //get inf conv and second_user
            ->with(['participants' => function($builder) use ($user) {
            $builder->where('id', '<>', $user->id)->select(['id','username','email','profile_image']);
        }])
        ->findOrFail($id);

        $messages = $conversation->messages() //get all messages first and second with inf 
            ->with(['user'=>function($query){$query->select(['id','username','email','profile_image']);}])
            ->where(function($query) use ($user) {
                $query->where(function($query) use ($user)
                 {
                        $query->where('user_id', $user->id)->whereNull('deleted_at'); 
                         //get all message auth not deleted ? what about second user
                    })
                    ->orWhereRaw('id IN (
                        SELECT message_id FROM recipients
                        WHERE recipients.message_id = messages.id
                        AND recipients.user_id = ?
                        AND recipients.deleted_at IS NULL
                    )', [$user->id]);
            })
            ->latest()->get();
           // ->paginate();
        return [
            'conversation' => $conversation,
            'messages' => $messages,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     //CREAT MESSAGE 
    public function store(Request $request)
    {
       // $status=$this->checkstatusfriend($request->user_id);
        //if($status="friends")
        $request->validate([
            'conversation_id' => [
                Rule::requiredIf(function() use ($request) {
                    return !$request->input('user_id');
                }),
                'int', 
                'exists:conversations,id',
            ],
            'user_id' => [
                Rule::requiredIf(function() use ($request) {
                    return !$request->input('conversation_id');
                }),
                'int', 
                'exists:users,id',
            ],
        ]);

        $user = Auth::user(); //sender

        $conversation_id = $request->post('conversation_id');
        $user_id = $request->post('user_id');  //Receive 

        DB::beginTransaction(); 
        try {
                // by conversationID
            if ($conversation_id) 
            {
                $conversation = $user->conversations()->findOrFail($conversation_id);

            } 
            else  
            { 
                // BY USERID
                //check if u=sender and receive have conv 
                //where has  relationship  condition on this relationship
                $conversation = Conversation::whereHas('participants', function ($builder) use ($user_id, $user)
             {
                 $builder->join('participants as participants2','participants2.conversation_id', '=', 'participants.conversation_id')
                         ->where('participants.user_id', '=', $user_id)
                         ->where('participants2.user_id', '=', $user->id);
             })->first();
               

                 // not found conv with user
                if (!$conversation) 
                {
                    $conversation = Conversation::create([
                        'user_id' => $user->id,  //owner conversation
                    ]);
                   //add sender and receiver into participants 
                   // insert in many to many use attack 
                    $conversation->participants()->attach([
                        $user->id => ['joined_at' => now()],  
                        $user_id => ['joined_at' => now()],
                    ]);
                }

            }
            $type = 'text';
            $message = $request->post('message');

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                
                $message = [
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mimetype' => $file->getMimeType(),
                    'file_path' => $file->store('attachments', [
                    'disk' => 'public'
                    ]),
                ];
                $type = 'attachment';
            }
            //add message 
            $message = $conversation->messages()->create([
                'user_id' => $user->id, //sender
                'type' => $type,
                'body' => $message,
            ]); 
            //add receipents to message 
            DB::statement('
                INSERT INTO recipients (user_id, message_id)
                SELECT user_id, ? FROM participants
                WHERE conversation_id = ?
                AND user_id <> ? 
            ', [$message->id, $conversation->id, $user->id]);

            $conversation->update([
                'last_message_id' => $message->id,
            ]);

            DB::commit();

          $message->load([
                'user'=>function($query)
            {
                $query->select(['id','username','email','profile_image']);
            }
        ,'conversation',
        'recipients'=>function($query)
        {
            $query->select(['id','username','email','profile_image']);
        },
        ]);
            event(new MessageCreated($message)); 
            //problem : event not know query in $message return all
            //solve :

        }
         catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
       // return $message;
       return $this->sendResponse($message, 'done send message Successfully!');
    }
    /*
    else
    {
        return 'message friends just';
    }
    */
    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        $user->sentMessages()
            ->where('id', '=', $id)
            ->update(['deleted_at' => Carbon::now(),]);
        if ($request->target == 'me') 
        {  
            // delete me just  
            //note : i am send message -> not storage in receipents 
            Recipient::where([
                'user_id' => $user->id,
                'message_id' => $id,
            ])->delete();
        } 
        else {
            //delete all 
            Recipient::where([
                'message_id' => $id,
            ])->delete();
        }
        //problem : last_message not update 


        return [
            'message' => 'deleted',
        ];
        return $this->sendResponse(true, 'done deleted message Successfully!');
    }
}
