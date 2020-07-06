<?php

namespace App\Console\Commands;

use App\Http\Controllers\TwitchController;
use Illuminate\Console\Command;

class GetCurrentTwitchStreams extends Command
{
    protected $signature = 'twitch:streams';
    protected $description = 'Get the current list of streamers on Twitch and notify the channel of new streams';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $twitch = new TwitchController;
        $streams = $twitch->getNewlyStartedStreams();

        $this->line(json_encode($streams));

        return 0;
    }
}
