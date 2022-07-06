<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class NotificationController extends Controller
{
    public function showAllNotify()
    {
        $user=Auth::User();
        
      $noty=$user->notifications;
      return $noty;
    }
    public function showAllUnreadNotify()
    {
        $user=Auth::User();
       return $user->unreadNotifications;
    }
    public function markAsReadNotify () 
    {
        $user=Auth::User();
        
        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
        //    $user->unreadNotifications()->update(['read_at' => now()]);
            }
            
            
    }
    

}
