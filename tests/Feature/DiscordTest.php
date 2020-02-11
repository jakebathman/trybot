<?php

namespace Tests\Feature;

use App\DiscordChannel;
use App\Http\Remotes\Discord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscordTest extends TestCase
{
    use RefreshDatabase;
    
    public $discordGuildId = '143966277327781889';

    /** @test */
    function it_can_create_a_new_discord_channel()
    {
        $response = $this->get('/api/discord/create_voice_channel');

        $response->assertStatus(200);
        
        $channel = DiscordChannel::first();

        return $channel->channel_id;
    }

    /** @test */
    function it_can_generate_a_channel_invite()
    {
        $this->markTestIncomplete();



        $response = $this->get('/api/discord/create_voice_channel');

        $response->assertStatus(200);
        
        print_r(DiscordChannel::all());
    }

    /**
     * @test
     * @depends it_can_create_a_new_discord_channel
    */
    function it_can_delete_a_discord_channel($discordChannelId)
    {
        print_r('channel: '.$discordChannelId);
        $discord = new Discord($this->discordGuildId);
        $result = $discord->deleteChannel($discordChannelId);

        $this->assertTrue($result);
        // $this->markTestIncomplete();

        // $response = $this->get('/api/discord/create_voice_channel');

        // $response->assertStatus(200);
    }
}
