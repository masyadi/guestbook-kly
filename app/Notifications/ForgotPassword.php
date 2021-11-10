<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Helper;

class ForgotPassword extends Notification
{
    use Queueable;

    protected $linkReset;
    protected $linkLogin;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($linkReset, $linkLogin)
    {
        $this->linkReset = $linkReset;
        $this->linkLogin = $linkLogin;
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
        $token = Helper::url_encode([
            'time'=> time(),
            'token'=> $notifiable->token,
            'email'=> $notifiable->email,
            'login_url' => $this->linkLogin
        ]);

        return (new MailMessage)
                ->subject(config('app.name') .': Minta Password Baru')
                ->greeting(__('Hai '.$notifiable->name))
                ->line(__('Anda baru saja meminta untuk mengganti password anda. Silakan klik tombol di bawah ini untuk reset password'))
                ->line(__('link ini berlaku jika tidak ada perubahan data lagi dalam waktu 2 jam'))
                ->action(__('Reset Password'), $this->linkReset .'?c='. $token)
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
