<?php

namespace Tests\Feature;

use App\Http\Models\Twitch;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlashCommandsTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    function it_can_handle_slash_discord()
    {
        $this->markTestIncomplete();

        // Middleware skip?
        $response = $this->get('/admin/slack');

        $response->assertStatus(200);
    }

    /** @test */
    function it_can_handle_slash_twitch()
    {
        // Add someone to track (an always-on stream)
        $user = factory(User::class)->create();
        factory(Twitch::class)->create([
            'user_id' => $user->id,
            'twitch_username' => 'stadium',
        ]);
        $user->fresh();

        $query = http_build_query([
            'user_id' => $user->slack_user_id,
            'user_name' => $user->slack_user_name,
            'team_id' => $user->slack_team_id,
            'team_domain' => $user->slack_team_domain,
            'text' => "",
        ]);

        $response = $this->get('/api/slack/slash/twitch?' . $query);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'text',
                'response_type',
                'attachments' => [
                    '*' => [
                        'fallback',
                        'title',
                        'title_link',
                        'text',
                        'image_url',
                        'mrkdwn_in',
                    ],
                ],
            ]);
    }

    /** @test */
    function it_can_handle_slash_twitch_list_with_new_user()
    {
        // Add someone to track (an always-on stream)
        factory(Twitch::class)->create([
            'user_id' => null,
            'twitch_username' => 'stadium',
        ]);

        $query = http_build_query([
            'user_id' => 123456,
            'user_name' => "foo",
            'team_id' => 987654,
            'team_domain' => "bar",
            'text' => "",
        ]);

        $response = $this->get('/api/slack/slash/twitch?' . $query);

        // Assert that a new user was created for this slack user
        $user = User::first();
        $this->assertNotEmpty($user);
        $this->assertEquals(123456, $user->slack_user_id);
        $this->assertEquals("foo", $user->slack_user_name);
        $this->assertEquals(987654, $user->slack_team_id);
        $this->assertEquals("bar", $user->slack_team_domain);
    }

    /** @test */
    function it_can_handle_slash_twitch_set()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    function it_can_handle_slash_twitch_delete()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    function it_can_handle_slash_twitch_help()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    function it_can_handle_slash_jizzme()
    {
        $query = http_build_query(['text' => '@trybot @jakebathman']);

        $response = $this->get('/api/slack/slash/jizzme?' . $query);

        $response
            ->assertStatus(200)
            ->assertJson(
                [
                    'text'  =>  '@trybot -> 8==:fist:D:sweat_drops:  :drooling_face: <- @jakebathman',
                    'response_type'  =>  'in_channel',
                ]
            );
    }

    /** @test */
    function it_can_handle_slash_codes()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    function it_can_handle_slash_google()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    function it_can_handle_slash_tz()
    {
        $this->markTestIncomplete();
    }
}
