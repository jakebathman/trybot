<?php

namespace App\Listeners;

use App\DiscordChannel;
use App\Events\DiscordChannelCreated;
use Illuminate\Support\Facades\Redis;

class LogDiscordChannelCreated
{
    public function __construct()
    {
        //
    }

    public function handle(DiscordChannelCreated $event)
    {
        // Add to MySQL so it can be deleted in a day
        DiscordChannel::create([
            'guild_id' => $event->guildId,
            'channel_id' => $event->channelId,
            'channel_name' => $event->channelName,
        ]);
    }
}
