<?php

namespace Tests\Feature;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use  \Tests\TestCase;
use  \Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginWithWrongValidation()
    {


        $password = 'passwordwewe';

        $appUser = User::factory()->create();

        $uri = 'api/auth/login';
        $response = $this->call(
            'POST',
            $uri,
            ['email' => $appUser->email, 'password' => $password], //parameters
            [], //cookies
            [], // files
            $this->headers(), // server
            []
        );

        $response->assertStatus(401)->assertExactJson(json_decode('{"message":"Invalid credentials","status_code":401,"status":false}', true));
    }

    /**
     * @test
     */
    public function testCorrectLogin()
    {

        $password = 'password';

        $appUser = User::factory()->create();


        $logginDetails = $this->post('api/auth/login', [
            'email' => $appUser->email,
            'password' => $password,
        ]);

        $logginDetails->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'token_type',
                    'user',
                ],
                'message',
                'status',
                'status_code',
            ]);
    }


    /** @test */
    public function testRefreshToken()
    {
        $appUser = User::factory()->create();
        $token = JWTAuth::fromUser($appUser);
        $uri = "/api/auth/refresh?token={$token}";

        $reply = $this->call(
            'PATCH',
            $uri,
            [], //parameters
            [], //cookies
            [], // files
            $this->headers($appUser), // server
            []
        );
        $reply->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token'
                ],
                'message',
                'status',
                'status_code',
            ]);
    }
 


    public function testInvalidateToken()
    {
        $this->refreshApplication();
        $appUser = User::factory()->create(['password' => app('hash')->make('password')]);
        $token = JWTAuth::fromUser($appUser);
        $uri = "/api/auth/invalidate?token={$token}";

        $reply = $this->call(
            'DELETE',
            $uri,
            [], //parameters
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
    }


    /** @test */
    public function registration_test()
    {
        $email =  $this->faker->unique()->safeEmail;
        $response = $this->post('api/auth/sign-up', [
            'name' => $this->faker->name,
            'email' => $email,
            'password' => 'Password1@',
        ]);
        // assert otp email sent to user and contains otp
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'token_type',
                ],
                'message',
                'status',
                'status_code',
            ]);
    }

    /** @test */
    public function register_a_member_with_existing_email()
    {

        $appUser = User::factory()->create(['password' => Hash::make('password')]);


        //call the url to post the member's info

        $response = $this->post('api/auth/sign-up', [
            'name' => $this->faker->name,
            'email' => $appUser->email,
            'password' => 'Password1@',
        ]);

        $response->assertStatus(400)
            ->assertJson(["status_code" => 400, "status" => false]);
    }
}
