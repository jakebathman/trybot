<?php

namespace Tests\Feature;

use App\DiscordChannel;
use App\Http\Remotes\Discord;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Arr;
use Tests\TestCase;

class DiscordTest extends TestCase
{
    use RefreshDatabase;
    
    public $discordGuildId = '143966277327781889';

    /** @test */
    function it_can_get_discord_channels()
    {
        $discord = new Discord($this->discordGuildId);

        $result = $discord->getChannels();

        $this->assertIsArray($result);
        $this->assertGreaterThan(0, count($result));
    }

    /** @test */
    function it_can_create_a_new_discord_channel_via_api_endpoint()
    {
        $response = $this->get('/api/discord/create_voice_channel');

        $response->assertStatus(200);
        
        $channel = DiscordChannel::first();

        $response->assertJsonStructure([
            'status',
            'data' => [
                'invite_url',
                'channel_name',
                'channel_id',
            ],
        ]);
        $response->assertJson([
            'status' => 'success',
        ]);
        $this->assertStringContainsStringIgnoringCase('discord.gg', $response->json('data.invite_url'));

        // Delete this new channel now
        $discord = new Discord($this->discordGuildId);
        $discord->deleteChannel(Arr::get($response->json(), 'data.channel_id'));
    }

    /** @test */
    function it_can_create_a_new_discord_channel()
    {
        $discord = new Discord($this->discordGuildId);
        $channelId = $discord->createChannel('foo-fake-' . microtime(true));

        $this->assertNotNull($channelId);

        // Delete this new channel now
        $discord->deleteChannel($channelId);
    }

    /**
     * @test
    */
    function it_can_delete_a_discord_channel()
    {
        $discord = new Discord($this->discordGuildId);
        $discordChannelId = $discord->createChannel('foo-fake-' . microtime(true));

        $result = $discord->deleteChannel($discordChannelId);

        $this->assertTrue($result);
    }
}
