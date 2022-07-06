<?php

namespace App\Notifications;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;


class FriendRequestAccept extends Notification implements ShouldQueue,ShouldBroadcast
{
    use Queueable;
    protected $user; // that accept request

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user=$user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    //$notifiable : receiver notify == User::whereId($sender_id)
    //$user : that accept request
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Password Changed Successfully')
                   // ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }
    }

