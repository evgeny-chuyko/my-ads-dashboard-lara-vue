<?php

namespace App\Http\Controllers\API\Auth;

use App\Events\UserLoggedOut;
use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="User logout",
     *     description="Logout current user and revoke access token",
     *     tags={"Authentication"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
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
        $user = $request->user()->load('role');
        
        $this->authService->logout($user);

        event(new UserLoggedOut($user));

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
