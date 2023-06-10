<?php

namespace App\Http\Controllers\Api;


use App\Transformers\UserTransformer;
use App\Models\User;
use App\Services\Feeds\FeedService;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;


class FeedController extends BaseController
{

    /**
     * @OA\Get(
     *   path="/api/feeds",
     *   summary="Fetch news feed",
     *      tags={"Feed"},
     *   @OA\Response(
     *     response=200,
     *     description=""
     *   ),
     *   @OA\Parameter(
     *     name="country",
     *     description="Filter by country",
     *     in="query"
     * ),
     *     @OA\Parameter(
     *     name="category",
     *     description="Filter by category",
     *     in="query",
     * ),
     *     @OA\Parameter(
     *     name="search",
     *     description="Search feeds source",
     *     in="query",
     * )
     * )
     **/

    public function feeds(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'country' => ['sometimes'],
                'search' => ['sometimes'],
                'category' => ['sometimes']
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $user = auth()->user();
            $feedService = new FeedService($user);

            $data = [
                'feeds' => $feedService->feeds(
                    [
                        'search' => $request->search ?? '',
                        'category' => $request->category,
                        'country'  => $request->country
                    ]
                ),
            ];

            return Response::Ok("Feeds", $data, $request);
        } catch (ValidationException $e) {
            $message = implode($e->errors());
            return Response::Problem($message, Response::ProblemResponseCode, $request);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return Response::Problem($message, Response::ServerErrorResponseCode, $request, $e);
        }
    }


    /**
     * @OA\Get(
     *   path="/api/meta",
     *   summary="Fetch news feed meta",
     *      tags={"Feed"},
     *   @OA\Response(
     *     response=200,
     *     description=""
     *   )
     * )
     **/

    public function meta(Request $request)
    {
        try {
           
            $feedService = new FeedService();

            $data = [
                'meta' => $feedService->meta()
            ];

            return Response::Ok("Feeds meta", $data, $request);
        } catch (ValidationException $e) {
            $message = implode($e->errors());
            return Response::Problem($message, Response::ProblemResponseCode, $request);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return Response::Problem($message, Response::ServerErrorResponseCode, $request, $e);
        }
    }

}
