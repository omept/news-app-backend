<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


$api = app('Dingo\Api\Routing\Router');


$api->version('v1', function ($api) {


    $api->group(
        [
            'namespace' => 'App\Http\Controllers\Api',
        ],
        function ($api) {

            $api->get('/hello-world', function () {
                return json_encode([
                    "message" => 'Hello, world!'
                ]);
            });

            $api->post('/auth/login', 'AuthController@login')->middleware('throttle:3,1');
            $api->post('/auth/sign-up', 'AuthController@sign_up')->middleware('throttle:5,1');
            $api->get('/feeds', 'FeedController@feeds')->middleware('throttle:10,1');
            $api->group(
                [
                    'middleware' => 'jwt.auth'
                ],
                function ($api) {

                    $api->patch('/auth/refresh', [
                        'uses' => 'AuthController@patchRefresh',
                        'as' => 'api.auth.refresh'
                    ]);
                    $api->delete('/auth/invalidate', [
                        'uses' => 'AuthController@deleteInvalidate',
                        'as' => 'api.auth.invalidate'
                    ]);
                }
            );
        }
    );
});
