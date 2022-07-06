<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class LikePostNotify extends Notification implements ShouldQueue,ShouldBroadcast{
    use Queueable;
    protected $sender,$post;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sender,$post)
    {
        $this->sender=$sender;
        $this->post=$post;
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
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $body=sprintf('your friend %s reaction to the post',$this->sender->username,);
        $url=sprintf(
            'http://127.0.0.1:8000/api/getpostid/%s',
            $this->post->id,
           );
        return [
        'body'=>$body,
        'action'=>$url,
        ];
    }
    public function toBroadcast($notifiable)
    {
        $body=sprintf('your friend %s reaction to the post',$this->sender->username,);
        $url=sprintf(
            'http://127.0.0.1:8000/api/getpostid/%s',
            $this->post->id,
           );

          return new BroadcastMessage( [
        'body'=>$body,
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
