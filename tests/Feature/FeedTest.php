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
     * Test that feeds api returns feeds with default category when to category is passed
     *
     * @return void
     */
    public function testDefaultFeedCategory()
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
                    "items",
                    "country"
                ]
            ]
        ]);
        $resArr = json_decode($response->getContent(), true);
        $this->assertTrue($resArr['data']['feeds']['category']['name'] == ucwords($defaultCategory->name));
    }

    /**
     * Test that feeds api returns feeds with default country when to country is passed
     *
     * @return void
     */
    public function testDefaultFeedCountry()
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

        $defaultCountry = FeedService::$defaultCountry;
        $response->assertOk();

        $response->assertJsonStructure([
            "data" => [
                "feeds" => [
                    "category",
                    "items",
                    "country",
                ]
            ]
        ]);
        $resArr = json_decode($response->getContent(), true);
        $this->assertTrue($resArr['data']['feeds']['country']['name'] == ucwords($defaultCountry));
    }
}
