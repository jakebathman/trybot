<?php

namespace App\Http\Remotes;

use App\Events\DiscordChannelCreated;
use Exception;
use Illuminate\Support\Facades\Http;
use Ixudra\Curl\Facades\Curl;

class Discord
{
    public $guildId;

    public function __construct($guildId)
    {
        $this->guildId = $guildId;
        $this->token = config('services.discord.trybot_token');
    }
    
    public function getChannels($groupByCategory = false)
    {
        $response = Http::withHeaders(['Authorization' => 'Bot ' . $this->token])
            ->get("https://discordapp.com/api/guilds/{$this->guildId}/channels");

            // if (isset($response->error)) {
        //     // Something went wrong with the request
        //     throw new Exception("Creating channel failed (code {$response->status}): {$response->error}");
        // }

        $channels = $response->json();

        if ($groupByCategory) {
            $channels = collect($channels)
            ->mapToGroups(function ($channel) {
                return [($channel['parent_id'] ?? 0) => $channel];
            })
            ->toArray();
        }

        return $channels;
    }

    public function createChannel($name)
    {
        $response = Curl::to("https://discordapp.com/api/guilds/{$this->guildId}/channels")
            ->withHeaders(['Authorization: Bot ' . $this->token])
            ->withData([
                'name' => $name,
                'type' => '2',
                'parent_id' => 676531715476553729, // puts it under "Temp" group
                'position' => 99,
            ])
            ->asJson()
            ->returnResponseObject()
            ->post();

        if (isset($response->error)) {
            // Something went wrong with the request
            throw new Exception("Creating channel failed (code {$response->status}): {$response->error}");
        }

        $channelId = $response->content->id;

        event(new DiscordChannelCreated($this->guildId, $channelId, $name));

        // The channel was created
        return $channelId;
    }

    public function getChannelInvite($channelId)
    {
        $response = Curl::to("https://discordapp.com/api/channels/{$channelId}/invites")
            ->withHeaders(['Authorization: Bot ' . $this->token])
            ->withData(['max_age' => 0])
            ->asJson()
            ->returnResponseObject()
            ->post();

        if (isset($response->error)) {
            // Something went wrong with the request
            throw new Exception("Invite failed (code {$response->status}): {$response->error}");
        }

        return 'https://discord.gg/' . $response->content->code;
    }

    public function deleteChannel($channelId)
    {
        $response = Curl::to("https://discordapp.com/api/channels/{$channelId}")
            ->withHeaders(['Authorization: Bot ' . $this->token])
            ->asJson()
            ->delete();

        if (isset($response->error)) {
            // Something went wrong with the request
            throw new Exception("Deleting channel failed (code {$response->status}): {$response->error}");
        }

        if (isset($response->id) && $channelId == $response->id) {
            return true;
        }

        throw new Exception("Deleting channel failed (code {$response->code}): {$response->message}");
    }
}
