<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Resources\V1\AuthTokenResource;

class AuthController extends Controller
{

    /**
     * Authenticate user and get bearer token to use with API.
     *
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *     tags={"Authentification"},
     *     path="/login",
     *     security={{"basicAuth":{}}},
     *     description="Creates new access token and invalidates previously issued tokens if any",
     *     @OA\RequestBody(
     *         description="User's credentials",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      format="email",
     *                      description="User's email address"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="User's password"
     *                  ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Access Token",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $user->tokens()->delete();
        return new AuthTokenResource($user->createToken('basic-token'));
    }


    /**
     * Invalidate all tokens issued to user.
     *
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *     tags={"Authentification"},
     *     path="/logout",
     *     security={{"sanctum":{}}},
     *     description="Invalidate all tokens issued to user",
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *     )
     * )
     */
    public function logout()
    {
        Auth::user()->tokens()->delete();
    }
}
