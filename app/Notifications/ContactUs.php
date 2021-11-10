<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ContactUs extends Notification
{
    use Queueable;

    private $name;
    private $subject;
    private $phone;
    private $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $subject, $phone = null, $message)
    {
        $this->name = $name;
        $this->subject = $subject;
        $this->phone = $phone;
        $this->message = $message;
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
        return (new MailMessage)
                    ->subject(config('app.name') .': '. $this->subject ?? $this->name .' mengirim pesan')
                    ->greeting(new HtmlString('Message From '. $this->name . ($this->phone ? ' || <a href="tel:'. $this->phone .'">'. $this->phone .'</a>' : null)))
                    ->line(new HtmlString($this->message));
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
