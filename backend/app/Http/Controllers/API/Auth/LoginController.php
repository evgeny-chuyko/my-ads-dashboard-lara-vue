<?php

namespace App\Http\Controllers\API\Auth;

use App\Events\UserLoggedIn;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;

class LoginController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="User login",
     *     description="Authenticate user and receive access token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="admin@myads.com",
     *                 description="User's email address"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 example="password",
     *                 description="User's password"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful authentication",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Admin User"),
     *                 @OA\Property(property="email", type="string", example="admin@myads.com"),
     *                 @OA\Property(
     *                     property="role",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Admin")
     *                 ),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 example="1|abc123def456...",
     *                 description="Bearer token for authorization"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The email field is required."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(LoginRequest $request)
    {
        $result = $this->authService->login($request->only('email', 'password'));

        event(new UserLoggedIn(
            $result['user'],
            $request->ip(),
            $request->userAgent() ?? 'Unknown'
        ));

        return response()->json([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ]);
    }
}
