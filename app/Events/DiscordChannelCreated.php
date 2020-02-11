<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DiscordChannelCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $guildId;
    public $channelId;
    public $channelName;

    public function __construct($guildId, $channelId, $channelName)
    {
        $this->guildId = $guildId;
        $this->channelId = $channelId;
        $this->channelName = $channelName;
    }
}
