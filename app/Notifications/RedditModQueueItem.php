<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class RedditModQueueItem extends Notification
{
    use Queueable;

    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $post = $this->item->get('data');

        $message = new SlackMessage;

        switch (strtolower($post->get('subreddit'))) {
            case 'fortniteleaks':
                $message->from('/r/FortniteLeaks', ':fortnite:');
                break;
            
            case '24hoursupport':
                $message->from('/r/24HourSupport', ':24hoursupport:');
                break;
            
            default:
                $message->from('Reddit', ':smiley_cat:');
                break;
        }

        $message->attachment(function ($attachment) use ($post) {
            $attachment
                ->color(str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT))
                ->title($post->get('title'), 'https://reddit.com' . $post->get('permalink'))
                ->fields([
                    'user' => $post->get('author'),
                    'type' => $post->get('post_hint', 'text'),
                ])
                ->content($post->get('selftext'))
                ->action('Open Mod Queue', 'https://www.reddit.com/r/mod/about/modqueue', 'primary');
            if ($post->get('is_self', true) == false) {
                $attachment->image($post->get('thumbnail'));
            }
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
