<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class userInvitation extends Notification
{
    use Queueable;

    public $sender;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sender)
    {
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
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
        $url = url('accept-invitation?c='.md5($notifiable->email));

        return (new MailMessage)
                ->subject($this->sender->name.' mengundang Anda di '.config('app.name'))
                ->greeting(__('Hai, '.ucwords($notifiable->name)))
                ->line(__($this->sender->name.' mengundang Anda sebagai \''.$notifiable->relrole->name.'\' di '.config('app.name')))
                ->line(__('Silakan klik tombol "SETUJU" berikut ini untuk menerima undangan'))
                ->action(__('SETUJU'), $url)
                ->line(__('Terima kasih telah menggunakan layanan ini'));
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
}
