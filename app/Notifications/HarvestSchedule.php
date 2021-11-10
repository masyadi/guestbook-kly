<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class HarvestSchedule extends Notification
{
    private $params;

    public function __construct(Array $params = [])
    {
        $this->params = $params;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Jadwal Panen')
            ->icon(asset('static/img/icon.png'))
            ->body('Sayuran dan buah siap panen. Yuk dipesan!')
            ->action('Lihat Sekarang', 'jadwal-panen')
            ->image(asset('static/img/logo.png'))
            ->data(array_merge(['id' => $notification->id], ['params' => $this->params]))
            ->options(['TTL' => 1000]);
    }
}
