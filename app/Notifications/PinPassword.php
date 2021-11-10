<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class PinPassword extends Notification
{
    use Queueable;

    public $sender;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        $digits = 4;
        $kode = rand(pow(10, $digits-1), pow(10, $digits)-1);
        $path = storage_path('pin_password');
        
        if(!\File::exists($path)) 
        {
            \File::makeDirectory($path, 0755, true, true);
        }
        
        \File::put($path.'/'.$notifiable->id.'.json', json_encode(['kode'=>$kode]));

        return (new MailMessage)
                ->subject(config('app.name') .': permintaan ganti password')
                ->greeting('Hi '. $notifiable->name. ',')
                ->line('Anda baru saja meminta kode untuk ganti password')
                ->line(new HtmlString('Ini adalah kode Anda <strong>'.$kode.'</strong>'));
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
