<?php

namespace App\Console\Commands;

use App\DiscordChannel;
use App\Http\Remotes\Discord;
use Exception;
use Illuminate\Console\Command;

class DeleteOldDiscordChannelsCommand extends Command
{
    
    protected $signature = 'discord:delete-old';

    protected $description = 'Delete old discord channels created using the /discord command';

    private $discord;

    public function __construct()
    {
        parent::__construct();

        $this->discord = new Discord('143966277327781889');
    }

    public function handle()
    {
        $channelsToKeep = DiscordChannel::shouldNotBeDeleted()->pluck('channel_id');
        $allChannels = $this->discord->getChannels(true);
        
        // Find correct parent
        foreach ($allChannels[0] as $channel) {
            if (strtolower($channel['name']) == 'temp') {
                $parentId = $channel['id'];
            }
        }
        

        collect($allChannels[$parentId])->each(function ($channel) use ($channelsToKeep) {
            if ($channelsToKeep->has($channel['id'])) {
                return;
            }

            echo "Deleting {$channel['name']}...";
            $deleted = false;

            try {
                $deleted = (new Discord($channel['guild_id']))->deleteChannel($channel['id']);
            } catch (Exception $th) {
                //throw $th;
            }
            
            if ($deleted) {
                $this->info("Success!");

                // Mark as deleted if it's in the database
                $c = DiscordChannel::where('channel_id', $channel['id'])->first();
                if ($c) {
                    $c['is_deleted'] = true;
                    $c->save();
                }
            } else {
                $this->error("Fail!");
            }
        });

        return 0;
    }
}
