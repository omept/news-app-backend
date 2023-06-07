<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Tymon\JWTAuth\Facades\JWTAuth;

class BaseController extends Controller
{

    use Helpers;

    public function user()
    {
        return JWTAuth::user();
    }
}
