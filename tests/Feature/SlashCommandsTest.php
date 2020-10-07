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
    function it_can_handle_slash_twitch()
    {
        // Add someone to track (an always-on stream)
        $user = factory(User::class)->create();
        factory(Twitch::class)->create([
            'user_id' => $user->id,
            'twitch_username' => 'stadium',
        ]);
        $user->fresh();

        $response = $this->get(route('api.slack.slash.twitch', [
            'user_id' => $user->slack_user_id,
            'user_name' => $user->slack_user_name,
            'team_id' => $user->slack_team_id,
            'team_domain' => $user->slack_team_domain,
            'text' => "",
        ]));

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
        // Create a new user
        $slackUserId = 'ABC123';
        $slackUserName = 'fooUser';
        $teamId = 'XYZ987';
        $teamDomain = 'foo';

        $user = factory(User::class)->create([
            'slack_user_id' => $slackUserId,
            'slack_user_name' => $slackUserName,
            'slack_team_id' => $teamId,
            'slack_team_domain' => $teamDomain,
        ]);
        factory(Twitch::class)->create([
            'user_id' => $user->id,
            'twitch_username' => 'stadium',
        ]);

        $response = $this->get(route('api.slack.slash.twitch', [
            'user_id' => $user->slack_user_id,
            'user_name' => $user->slack_user_name,
            'team_id' => $user->slack_team_id,
            'team_domain' => $user->slack_team_domain,
            'text' => "",
        ]));

        // Assert that a new user was created for this slack user
        $user = User::first();
        $this->assertNotEmpty($user);
        $this->assertEquals($slackUserId, $user->slack_user_id);
        $this->assertEquals($slackUserName, $user->slack_user_name);
        $this->assertEquals($teamId, $user->slack_team_id);
        $this->assertEquals($teamDomain, $user->slack_team_domain);
    }

    /** @test */
    function it_can_handle_slash_twitch_set()
    {
        // Create a new user with no twitch relationship (yet)
        $user = factory(User::class)->create();

        $response = $this->get(route('api.slack.slash.twitch', [
            'user_id' => $user->slack_user_id,
            'user_name' => $user->slack_user_name,
            'team_id' => $user->slack_team_id,
            'team_domain' => $user->slack_team_domain,
            'text' => "set new_twitch_username",
        ]));
        
        $response->assertOk();
        
        $user->fresh();
        $this->assertEquals($user->getTwitchUsername(), 'new_twitch_username');
    }

    /** @test */
    function it_can_handle_slash_twitch_add_existing_error()
    {
        // Create a new user
        $user = factory(User::class)->create();
        factory(Twitch::class)->create([
            'user_id' => $user->id,
            'twitch_username' => 'old_username',
        ]);

        $response = $this->get(route('api.slack.slash.twitch', [
            'user_id' => $user->slack_user_id,
            'user_name' => $user->slack_user_name,
            'team_id' => $user->slack_team_id,
            'team_domain' => $user->slack_team_domain,
            'text' => "set new_twitch_username",
        ]));

        $response->assertOk();
        $this->assertEquals("Sorry, you've already set your Twitch username to *old_username*", $response->getContent());
    }


    /** @test */
    function it_can_handle_slash_twitch_delete()
    {
        // Create a new user
        $user = factory(User::class)->create();
        factory(Twitch::class)->create([
            'user_id' => $user->id,
            'twitch_username' => 'old_username',
        ]);

        $response = $this->get(route('api.slack.slash.twitch', [
            'user_id' => $user->slack_user_id,
            'user_name' => $user->slack_user_name,
            'team_id' => $user->slack_team_id,
            'team_domain' => $user->slack_team_domain,
            'text' => "delete",
        ]));

        $response->assertOk();
        $this->assertEquals("I've removed *old_username*! You can set your name using `/twitch set your_twitch_username`", $response->getContent());
    }

    /** @test */
    function it_can_handle_slash_twitch_help()
    {
        $response = $this->get(route('api.slack.slash.twitch', [
            'text' => "help",
        ]));

        $this->assertStringContainsString("TryBot will automatically notify #casual when someone starts streaming", $response->getContent());
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
        $response = $this->get(route('api.slack.slash.codes'));

        $response->assertOk();
    }
}
