<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;

class RegisterController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register a new user",
     *     description="Create a new user account and receive an authentication token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe", description="User's full name"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com", description="User's email address"),
     *             @OA\Property(property="password", type="string", format="password", example="password", minLength=8, description="User's password (min 8 characters)"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password", description="Password confirmation")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User successfully registered",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(
     *                     property="role",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Publisher")
     *                 ),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="token", type="string", example="2|abc123def456...", description="Bearer token for authentication")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The email has already been taken."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email has already been taken.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user->load('role')),
            'token' => $token,
        ], 201);
    }
}
