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
    
    public $discord;
    public $discordGuildId = '143966277327781889';

    public function setUp(): void
    {
        parent::setUp();

        $this->discord = new Discord($this->discordGuildId);
    }

    /** @test */
    function it_can_delete_old_channels_using_command()
    {
        // Create a channel
        $channelName = 'foo-fake-' . microtime(true);
        $channelId = $this->discord->createChannel($channelName);

        // Make sure it's in the database
        $channel = DiscordChannel::where('channel_id', $channelId)->first();
        $this->assertNotNull($channel);

        // Back-date the channel so it'd be deleted as an old one
        $channel->created_at = now()->subDays(2);
        $channel->save();
        
        // Run the command to delete old channels
        $this->artisan('discord:delete-old')
            ->assertExitCode(0);

        // Make sure marked as deleted in the database
        $this->assertTrue($channel->fresh()->is_deleted);

        // Get channels and make sure it actually doesn't exist anymore
        $result = $this->discord->getChannels();

        $this->assertEmpty(collect($result)->where('name', $channelName));
    }

    /** @test */
    function it_does_not_delete_channels_recently_created_using_command()
    {      

        // Create two channels
        $channelName1 = 'foo-fake-' . microtime(true);
        $channelName2 = 'bar-keep-' . microtime(true);
        $channelId1 = $this->discord->createChannel($channelName1);
        $channelId2 = $this->discord->createChannel($channelName2);

        // Back-date only one of the channels
        $channel1 = DiscordChannel::where('channel_id', $channelId1)->first();
        $channel1->created_at = now()->subDays(2);
        $channel1->save();

        // Run the command to delete old channels
        $this->artisan('discord:delete-old')
            ->assertExitCode(0);

        // Make sure channel1 is marked as deleted in the database
        $this->assertTrue($channel1->fresh()->is_deleted);

        // Get channels and make sure it actually doesn't exist anymore
        $result = collect($this->discord->getChannels());

        $this->assertEmpty($result->where('name', $channelName1));

        // Make sure the channel we kept is still there, and still in the database
        $this->assertNotEmpty($result->where('name', $channelName2));
        $this->assertCount(1, DiscordChannel::where('channel_id', $channelId2)->get());

        // Delete channel2 (so it's not just hanging around)
        $this->discord->deleteChannel($channelId2);
    }

    /** @test */
    function it_can_get_discord_channels()
    {

        $result = $this->discord->getChannels();

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
        $this->discord->deleteChannel(Arr::get($response->json(), 'data.channel_id'));
    }

    /** @test */
    function it_can_create_a_new_discord_channel()
    {
        $channelId = $this->discord->createChannel('foo-fake-' . microtime(true));

        $this->assertNotNull($channelId);

        // Delete this new channel now
        $this->discord->deleteChannel($channelId);
    }

    /**
     * @test
    */
    function it_can_delete_a_discord_channel()
    {
        $discordChannelId = $this->discord->createChannel('foo-fake-' . microtime(true));

        $result = $this->discord->deleteChannel($discordChannelId);

        $this->assertTrue($result);
    }
}
