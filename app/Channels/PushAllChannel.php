<?php


namespace App\Channels;

use App\Service\Pushall;
use Illuminate\Notifications\Notification;

class PushAllChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        ['title' => $title, 'text' => $text] = $notification->toPushAll($notifiable);

        if (method_exists($notifiable, 'configNotificationPushAll')) {
            ['apiKey'=>$apiKey, 'id' => $id] = $notifiable->configNotificationPushAll();
            (new Pushall($apiKey, $id))->send($title, $text);
        } else {
            $title = ''
            push_all($title, $text);
        }
    }
}