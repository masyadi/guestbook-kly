<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NotifMessage extends Notification
{
    use Queueable;

    private $sender;
    private $content;
    private $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sender, $content, $link)
    {
        $this->sender = $sender;
        $this->content = $content;
        $this->link = $link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return config('app.enabled_socket') ? ['mail', 'database', 'broadcast'] : ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if( $notifiable->email )
        {
            return (new MailMessage)
                        ->subject(config('app.name') .': '.$this->sender->name.' mengirimkan pesan')
                        ->greeting('Pesan dari '. $this->sender->name)
                        ->line(strip_tags($this->content))
                        ->action(__('Balas'), $this->link);
        }
    }

    public function toDatabase($notifiable)
    {
        return [
            'sender_id' => $this->sender->id,
            'description' => $this->sender->name.' mengirimkan pesan',
            'url' => $this->link
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'photo' => $this->sender->photo ? \Helper::imgSrc($this->sender->photo, config('app.thumb_size')) : asset('static/png/no-image.png'),
            'description' => $this->sender->name.' '.__('mengirimkan pesan'),
            'url' => $this->link
        ]);
        
    }
}
