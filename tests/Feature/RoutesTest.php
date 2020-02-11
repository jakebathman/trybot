<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /** @test */
    function it_gets_slack_event_types()
    {
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
