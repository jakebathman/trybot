<?php

namespace Tests\Feature;

use Tests\TestCase;

class TwitchTest extends TestCase
{
    /** @test */
    function it_gets_twitch_endpoint()
    {
        $response = $this->json('GET', '/twitch');

        $response
            ->assertStatus(200)
            ->assertJson([
              'streamers'  =>  [],
              'results'  =>  [],
              ]);
    }

    /** @test */
    function it_gets_twitch_id_for_username()
    {
        $response = $this->json('GET', '/twitch/jakebathman');

        $response
            ->assertStatus(200)
            ->assertSeeText('58202671');
    }
}
