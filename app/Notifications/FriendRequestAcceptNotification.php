<?php

namespace App\Notifications;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;



class FriendRequestAcceptNotification extends Notification implements ShouldQueue,ShouldBroadcast
{
    use Queueable;
    protected $receiver; // that accept request

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($receiver)
    {
        $this->receiver=$receiver;
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
        return ['broadcast','database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $body=sprintf(
            '%s has agreed to ask your friendship',
            $this->receiver->username,
        );
        $url=sprintf(
            'http://127.0.0.1:8000/api/viewprofile/%s',
            $this->receiver->id
           );
        return [
            'body'=>$body,
            'photo'=>$this->receiver->profile_image,
            'action'=>$url,
        ];
    }
    public function toBroadcast($notifiable)
    {
        $body=sprintf(
            '%s has agreed to ask your friendship',
            $this->receiver->username,
        );
        $url=sprintf(
            'http://127.0.0.1:8000/api/viewprofile/%s',
            $this->receiver->id
           );
        return new BroadcastMessage( [
            'data'=>[
            'body'=>$body,
            'photo'=>$this->receiver->profile_image,
          'action'=>$url,
            ]
        ]);
    }
}
