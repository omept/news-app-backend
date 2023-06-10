<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Feeds\Adapter;
use Database\Seeders\DefaultCategoriesSeed;
use  \Tests\TestCase;
use  \Illuminate\Foundation\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;

class SettingsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that user can update preferences
     *
     * @return void
     */
    public function testUserPrefUpdate()
    {
        $this->refreshApplication();
        $this->seed(DefaultCategoriesSeed::class);
        $appUser = User::factory()->create(['password' => app('hash')->make('password')]);
        $uri = "/api/auth/user/settings";

        $reply = $this->call(
            'POST',
            $uri,
            [
                'country' => 'usa',
                'provider' => Adapter::NewscatcherApi,
                'category' => 'entertainment',
            ], //parameters
            [], //cookies
            [], // files
            $this->headers($appUser), // server
            []
        );

        $reply->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'message',
                'status',
                'status_code',
            ]);
        $appUser = $appUser->fresh();
        $settings = json_decode($appUser->settings, true);
        $this->assertTrue($settings['country'] == 'usa');
        $this->assertTrue($settings['category'] == 'entertainment');
        $this->assertTrue($appUser->provider_key == Adapter::NewscatcherApi);
    }
}
