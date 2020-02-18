<?php

namespace Tests\Feature;

use App\Http\Remotes\Reddit;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedditTest extends TestCase
{
    use RefreshDatabase, ArraySubsetAsserts;

    /**
     * @test
     */
    function it_loads_reddit_modqueue_json()
    {
        $reddit = new Reddit;
        $response = $reddit->getModqueue();

        $this->assertIsArray($response);
        self::assertArraySubset(
            [
                'kind' => 'Listing',
                'data'  =>  [
                    'children' => [],
                    'after' => '',
                    'before' => '',
                ],
            ],
            $response
        );
    }
}
