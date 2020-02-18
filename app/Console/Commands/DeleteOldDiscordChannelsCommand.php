<?php

namespace App\Console\Commands;

use App\DiscordChannel;
use App\Http\Remotes\Discord;
use Illuminate\Console\Command;

class DeleteOldDiscordChannelsCommand extends Command
{
    
    protected $signature = 'discord:delete-old';

    protected $description = 'Delete old discord channels created using the /discord command';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        DiscordChannel::shouldBeDeleted()->each(function ($channel) {
            echo "Deleting {$channel->channel_name}...";
            $deleted = (new Discord($channel->guild_id))->deleteChannel($channel->channel_id);
            if ($deleted) {
                $this->info("Success!");
                $channel->is_deleted = true;
                $channel->save();
            } else {
                $this->error("Fail!");
            }
        });
    }
}
