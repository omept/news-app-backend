<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Services\Feeds\FeedService;
use Tymon\JWTAuth\Facades\JWTAuth;
use  \Tests\TestCase;
use  \Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class FeedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test the feed comes from default values when called
     *
     * @return void
     */
    public function testDefaultFeed()
    {
        $uri = 'api/feeds';
        $response = $this->call(
            'GET',
            $uri,
            [], //params
            [], //cookies
            [], // files
            $this->headers(), // server
            []
        );

        //seed default category
        Category::factory()->create();

        $feedService = new FeedService();
        $defaultCategory = $feedService->defaultCategory();
        $response->assertOk();

        $response->assertJsonStructure([
            "data" => [
                "feeds" => [
                    "category",
                    "items"
                ]
            ]
        ]);
        $resArr = json_decode($response->getContent(), true);
        $this->assertTrue($resArr['data']['feeds']['category']['name'] == ucwords($defaultCategory->name));
    }
}
