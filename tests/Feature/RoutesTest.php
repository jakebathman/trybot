<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_gets_slack_event_types()
    {
        $this->markTestIncomplete();

        // Middleware skip?
        $response = $this->get('/admin/slack');

        $response->assertStatus(200);
    }

    /** @test */
    function it_gets_root_route()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
