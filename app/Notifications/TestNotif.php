<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage,
    NotificationChannels\WebPush\WebPushChannel;

class TestNotif extends Notification
{
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Dummy Notif')
            ->icon(asset('static/img/icon.png'))
            ->body('Ini test send notif!')
            ->action('Lihat Sekarang', 'jadwal-panen')
            ->image(asset('static/img/logo.png'))
            ->data(['id' => $notification->id])
            ->options(['TTL' => 1000]);
    }
}