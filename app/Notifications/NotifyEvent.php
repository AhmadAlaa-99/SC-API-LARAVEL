<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotifyEvent extends Notification implements ShouldQueue,ShouldBroadcast
{
    use Queueable;
    protected $event,$user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($event,$user)
    {
        $this->event=$event;
        $this->user=$user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
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
     * Get the a rray representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $body=sprintf(
            '% friends participate in this event ,since %',
            $this->user->username,
            $this->event->count,
           );
           $url=sprintf(
            'http://127.0.0.1:8000/api/showEvent/%s',
            $this->event->id
           );
        return [
        'body'=>$body ,
        'photo'=>$this->user->profile_image,
       'action'=>$url,
        ];
    }
    public function toBroadcast($notifiable)
    {
        $body=sprintf(
            '% friends participate in this event ,since %',
            $this->user->username,
            $this->event->count,
           );
           $url=sprintf(
            'http://127.0.0.1:8000/api/showEvent/%s',
            $this->event->id
           );
        return new BroadcastMessage
        ( [
        'body'=>$body ,
        'photo'=>$this->user->profile_image,
       'action'=>$url,
        ]);

        
    }
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
