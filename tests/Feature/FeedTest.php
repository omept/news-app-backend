<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Services\FeedService;
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

        $feedService = new FeedService();
        $defaultCategory = Category::factory()->create();
        $response->assertOk();
        $response->assertSee([
            "data" => [
                "feeds" => [
                    "category" => $feedService->defaultCategory()
                ]
            ]
        ]);
        // $response = $this->call(
        //     'POST',
        //     $uri,
        //     ['email' => $appUser->email, 'password' => $password], //parameters
        //     [], //cookies
        //     [], // files
        //     $this->headers(), // server
        //     []
        // );

        $response->assertStatus(401)->assertExactJson(json_decode('{"message":"Invalid credentials","status_code":401,"status":false}', true));
    }
}
