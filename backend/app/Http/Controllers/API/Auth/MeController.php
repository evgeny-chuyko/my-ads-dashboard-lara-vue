<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/auth/me",
     *     summary="Get current user",
     *     description="Retrieve authenticated user information",
     *     tags={"Authentication"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Current user information",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Admin User"),
     *             @OA\Property(property="email", type="string", example="admin@myads.com"),
     *             @OA\Property(
     *                 property="role",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Admin")
     *             ),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function __invoke(Request $request)
    {
        return new UserResource($request->user()->load('role'));
    }
}
