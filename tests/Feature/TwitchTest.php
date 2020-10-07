<?php

namespace Tests\Feature;

use App\Http\Controllers\TwitchController;
use App\Http\Models\Twitch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwitchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    function it_loads_twitch_route()
    {
        $response = $this->json('GET', '/twitch');

        $response
            ->assertStatus(200)
            ->assertJson(
                [
                    'streamers'  =>  [],
                    'results'  =>  [],
                ]
            );
    }

    /**
     * @test
     */
    function it_returns_game_name_for_game_id()
    {
        $gameId = "491931";
        $gameTitle = "Escape From Tarkov";
        $controller = new TwitchController;

        // Returns the game as a string
        $this->assertEquals($gameTitle, $controller->getGameForGameId($gameId));

        // Returns null for an unknown gameab
        $unknownGameId = "1234567890";
        $this->assertNull($controller->getGameForGameId($unknownGameId));
    }

    /**
     * @test
     */
    function it_returns_twitch_user_id_for_username()
    {
        $username = "jakebathman";
        $userId = "58202671";

        $response = $this->get("/twitch/{$username}");
        $response
            ->assertStatus(200)
            ->assertSeeText($userId);
    }

    /** @test */
    function it_gets_streams_info_via_twitch_streams_command()
    {
        $this->artisan('twitch:streams')
            ->expectsOutput('{"streamers":[],"results":[]}')
            ->assertExitCode(0);

        // Now add a channel and try again (we'll test the output elsewhere)
        factory(Twitch::class)->create([
            'user_id' => 1,
            'twitch_username' => 'stadium',
        ]);

        $this->artisan('twitch:streams')
            ->assertExitCode(0);
    }

    /** @test */
    function it_gets_newly_started_streams()
    {
        $data = (new TwitchController)->getNewlyStartedStreams();

        $this->assertArrayHasKey('streamers', $data);
        $this->assertArrayHasKey('results', $data);

        // Now add a channel and try again (we'll test the output elsewhere)
        factory(Twitch::class)->create([
            'user_id' => 1,
            'twitch_username' => 'stadium',
        ]);
        $data = (new TwitchController)->getNewlyStartedStreams();

        $this->assertArrayHasKey('streamers', $data);
        $this->assertArrayHasKey('results', $data);
        $this->assertCount(1, $data['streamers']);
        
        $stream = $data['streamers'][0];
        $this->assertEquals('209958706', $stream['user_id']);
        $this->assertEquals('live', $stream['type']);
        $this->assertEquals('Sports', $stream['game']);
    }
}
