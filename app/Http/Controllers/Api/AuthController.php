<?php

namespace App\Http\Controllers\Api;


use App\Transformers\UserTransformer;
use App\Models\User;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;


/**
 * @OA\Swagger(
 *     schemes={"https", "http"},
 *     consumes={"application/x-www-form-urlencoded"},
 *     @OA\Info(
 *         version="1.0",
 *         title="News App  API",
 *         description="This is the API service for the app",
 *         @OA\Contact(
 *             url="#"
 *         ),
 *     ),
 *     @OA\ExternalDocumentation(
 *         description="Find out more about the app",
 *         url="#"
 *     )
 * )
 */
class AuthController extends BaseController
{


    /**
     * @OA\Post(
     *   path="/api/auth/login",
     *   summary="Logs the user in and sends JWT token to be sent with every request with the user's detail",
     *      tags={"Authentication"},
     *   @OA\Response(
     *     response=200,
     *     description=""
     *   ),
     *   @OA\Parameter(
     *     name="email",
     *     description="User's email",
     *     required=true,
     *     in="query"
     * ),
     *     @OA\Parameter(
     *     name="password",
     *     description="User's password",
     *     required=true,
     *     in="query",
     * )
     * )
     **/

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|max:255',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $credentials = ['email' => $request->email, 'password' => $request->password];
            $token = JWTAuth::attempt($credentials);

            if (!$token) {
                return $this->onUnauthorized($request);
            }

            JWTAuth::setToken($token);
            // All good so return the token
            return $this->onAuthorized($token, $request);
        } catch (JWTException $e) {
            // Something went wrong whilst attempting to encode the token

            return $this->onJwtGenerationError($e);
        } catch (ValidationException $e) {
            $message = "Email and password are required";
            return Response::Problem($message, Response::ProblemResponseCode, $request);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return Response::Problem($message, Response::ServerErrorResponseCode, $request, $e);
        }
    }


    /**
     * @OA\Post(
     *   path="/api/auth/sign-up",
     *   summary="Register the user and sends JWT token to be sent with every request with the user's detail",
     *      tags={"Authentication"},
     *   @OA\Response(
     *     response=200,
     *     description="authorization token with user's data"
     *   ),
     *   @OA\Parameter(
     *     name="email",
     *     description="User's email  ",
     *     required=true,
     *     in="query"
     * ),
     *   @OA\Parameter(
     *     name="name",
     *     description="User's name",
     *     required=true,
     *     in="query"
     * ),
     *   @OA\Parameter(
     *     name="paytag",
     *     description="User's unique paytag.",
     *     required=true,
     *     in="query"
     * ),
     *   @OA\Parameter(
     *     name="country_id",
     *     description="User's country Id. This is used in setting the payment provider to attach to the user.",
     *     required=true,
     *     in="query"
     * ),
     *     @OA\Parameter(
     *     name="password",
     *     description="User's password",
     *     required=true,
     *     in="query",
     * ),
     *     @OA\Parameter(
     *     name="transfer_pin",
     *     description="User's transfer_pin",
     *     required=true,
     *     in="query",
     * )
     * )
     */
    public function sign_up(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|unique:users|max:100',
                'password' => 'required',
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'name' => $request->name,
            ]);

            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                $message = "Error occurred while creating your auth token. The credentials passed failed to match internally. ";
                $user->delete();
                return Response::Problem($message, Response::ProblemResponseCode, $request);
            }

            $message = "Registration was successful.";

            // All good so return the token
            $data = [
                'access_token' => $token,
                'token_type' => 'bearer',
            ];
            $data = array_merge(
                $data,
                $this->userData($user)
            );
            return Response::Ok($message, $data, $request);
        } catch (ValidationException $e) {
            $message = "Validation error occurred. " . (implode(' ', Arr::flatten($e->errors())));
            return Response::Problem($message, Response::ProblemResponseCode, $request);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return Response::Problem($message, Response::ServerErrorResponseCode, $request, $e);
        }
    }

    private function userData(User $passedUser = null): array
    {

        $transformer = new UserTransformer();
        $user = $passedUser ?? JWTAuth::user();

        return [
            'user' => $transformer->transform($user)
        ];
    }


    /**
     * What response should be returned on error while generate JWT.
     *
     * @return JsonResponse
     */
    protected function onJwtGenerationError($e)
    {
        return Response::Problem($e->getMessage(), "500", null);
    }

    /**
     * What response should be returned on authorized.
     *
     * @return JsonResponse
     */
    protected function onAuthorized($token, $request, $user = null)
    {
        if (is_null($user)) {
            $user = JWTAuth::toUser();
        }

        $token_created_at = now()->toDateTimeString();  // in minutes
        $newToken = JWTAuth::fromUser($user);

        $data = array_merge(
            [
                'access_token' => $newToken,
                'token_type' => 'bearer',
            ],
            $this->userData($user)
        );

        return Response::Ok("Successful", $data, $request);
    }


    /**
     * What response should be returned on invalid credentials.
     *
     * @return JsonResponse
     */
    protected function onUnauthorized($request)
    {
        return Response::Problem('Invalid credentials', Response::UnAuthorisedResponseCode, $request);
    }


    /**
     * Invalidate a token.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Delete(
     *   path="/api/auth/invalidate",
     *   summary="Invalidate/Delete user authorization token using previous token",
     *         tags={"Authentication"},
     *   @OA\Response(
     *     response=200,
     *     description="success message"
     *   ),
     *   @OA\Parameter(
     *     name="token",
     *     description="authorization token",
     *     required=true,
     *     in= "query",
     * )
     * )
     */
    public function deleteInvalidate(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'token' => 'required'
            ]);

            if ($validator->fails())
                throw new ValidationException($validator);


            $token = JWTAuth::parseToken();

            $token->invalidate();
            $message = "token invalidated successfully";
            return Response::Ok($message, [], $request);
        } catch (ValidationException $e) {
            $message = "token is required";
            return Response::Problem($message, Response::ProblemResponseCode, $request);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return Response::Problem($message, Response::ServerErrorResponseCode, $request, $e);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Patch(
     *   path="/api/auth/refresh",
     *   summary="Refresh user authorization token details using previous token",
     *          tags={"Authentication"},
     *   @OA\Response(
     *     response=200,
     *     description="new authorization token"
     *   ),
     *   @OA\Parameter(
     *     name="token",
     *     description="authorization token",
     *     required=true,
     *     in= "query",
     * )
     * )
     */
    public function patchRefresh(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'token' => 'required'
            ]);

            if ($validator->fails())
                throw new ValidationException($validator);


            $token = JWTAuth::parseToken();

            $newToken = $token->refresh();
            $message = 'Token refreshed successfully';

            return Response::Ok($message, [
                'token' => $newToken
            ], $request);
        } catch (ValidationException $e) {
            $message = "token is required";
            return Response::Problem($message, Response::ProblemResponseCode, $request);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return Response::Problem($message, Response::ServerErrorResponseCode, $request, $e);
        }
    }
}
