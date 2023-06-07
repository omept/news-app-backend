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
            'namespace' => 'App\Http\Controllers',
        ],
        function ($api) {

            $api->get('/hello-world', function () {
                return json_encode([
                    "message" => 'Hello world.'
                ]);
            });

            $api->post('/auth/login', 'APIAuthController@login')->middleware('throttle:3,1');
            $api->post('/auth/sign-up', 'APIAuthController@sign_up')->middleware('throttle:5,1');
            $api->group(
                [
                    'middleware' => 'jwt.auth'
                ],
                function ($api) {

                    $api->patch('/auth/refresh', [
                        'uses' => 'APIAuthController@patchRefresh',
                        'as' => 'api.auth.refresh'
                    ]);
                    $api->delete('/auth/invalidate', [
                        'uses' => 'APIAuthController@deleteInvalidate',
                        'as' => 'api.auth.invalidate'
                    ]);
                }
            );
        }
    );
});
