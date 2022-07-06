<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Recipient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\http\Controllers\BaseController as BaseController;

class ConversationsController extends BaseController
{

    // get all conversation for authUser 
    public function index()
    {
        $user = Auth::user();
        $conversation= $user->conversations()->with([
            'lastMessage', 'participants' => function($builder) use ($user) {$builder->where('id', '<>', $user->id);},
            ])->withCount([
                'recipients as new_messages' => function($builder) use ($user) {
                    $builder->where('recipients.user_id', '=', $user->id)
                        ->whereNull('read_at');
                }
            ])->get();
            return $this->sendResponse($conversation, 'All Conversations ');

    }


    // Get ConversationID
    public function show($id)
    {
        $user = Auth::user();
        return $user->conversations()->with([
            'lastMessage',
            'participants' => function($builder) use ($user) {
                $builder->where('id', '<>', $user->id);
            },])
            ->withCount([
                'recipients as new_messages' => function($builder) use ($user) {
                    $builder->where('recipients.user_id', '=', $user->id)
                        ->whereNull('read_at');
                }
            ])->findOrFail($id);
    }

    //read at in tsble redcepents
    public function markAsRead($id)
    {
     Recipient::where('user_id', '=', Auth::id())
            ->whereNull('read_at')
            ->whereRaw('message_id IN (
                SELECT id FROM messages WHERE conversation_id = ?
            )', [$id])
            ->update([
                'read_at' => Carbon::now(),
            ]);
        return [
            'message' => 'Messages marked as read',
        ];
    }
   
    public function destroy($id)
      //delete just in owner and stay in useroTHER
    {
        // Delete all message in convID and that authUser receipents
        Recipient::where('user_id', '=', Auth::id())
            ->whereRaw('message_id IN (  
                SELECT id FROM messages WHERE conversation_id = ?
            )', [$id])
            ->delete();
               return [
            'message' => 'Conversation deleted',
        ];
    }
}
