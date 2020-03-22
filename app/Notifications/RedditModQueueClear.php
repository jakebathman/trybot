<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class RedditModQueueClear extends Notification
{
    use Queueable;

    public $item;

    public function __construct()
    {
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $message = new SlackMessage;

        $message->attachment(function ($attachment) {
            $attachment
                ->color('#00bec7')
                ->title('Mod Queue Clear');
        });

        return $message;
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
