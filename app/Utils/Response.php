<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;

class Response
{

    const UnAuthorisedResponseCode = 401;
    const ProblemResponseCode = 400;
    const ServerErrorResponseCode = 500;

    const UnprocessableEntity = 422;
    //
    //StatusBadRequest
    //400
    //
    //StatusOK
    //200
    //
    //Server Error
    //500


    const RESPONSE_STATUS_BAD_REQUEST = 400;
    const RESPONSE_STATUS_OK = 200;
    const RESPONSE_STATUS_SERVER_ERROR = 500;



    static function Problem($message = null, $status_code = null, $request = null, $trace = null)
    {
        $code = ($status_code != null) ? $status_code : "404";
        $body = [
            'message' => "$message",
            'status_code' => $status_code,
            'status' => false
        ];


        if (!is_null($trace)) {
            Log::info($trace);
        }


        return response()->json($body)->setStatusCode($code);
    }


    static function Ok($message = null, $data = [])
    {
        $body = [
            'message' => $message ?? "",
            'data' => $data,
            'status' => true,
            'status_code' => 200,
        ];

        return response()->json($body);
    }
}
