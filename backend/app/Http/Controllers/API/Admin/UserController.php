<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Admin\AdminService;

class UserController extends Controller
{
    public function __construct(
        private AdminService $adminService
    ) {}

    /**
     * @OA\Get(
     *     path="/admin/users",
     *     summary="Get all users",
     *     description="Retrieve list of all users (Admin only)",
     *     tags={"Admin - Users"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of all users",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Admin User"),
     *                 @OA\Property(property="email", type="string", example="admin@myads.com"),
     *                 @OA\Property(
     *                     property="role",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Admin")
     *                 ),
     *                 @OA\Property(property="status", type="string", example="active", enum={"active", "banned"}),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Admin access required",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Access denied. Admin privileges required.")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $users = $this->adminService->getAllUsers();

        return UserResource::collection($users);
    }

    /**
     * @OA\Post(
     *     path="/admin/users/{id}/ban",
     *     summary="Ban user",
     *     description="Ban a user account (Admin only)",
     *     tags={"Admin - Users"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User banned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=2),
     *             @OA\Property(property="name", type="string", example="Publisher User"),
     *             @OA\Property(property="email", type="string", example="publisher@myads.com"),
     *             @OA\Property(
     *                 property="role",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="Publisher")
     *             ),
     *             @OA\Property(property="status", type="string", example="banned"),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Admin access required"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function ban(User $user)
    {
        $user = $this->adminService->banUser($user);

        return new UserResource($user);
    }

    /**
     * @OA\Post(
     *     path="/admin/users/{id}/unban",
     *     summary="Unban user",
     *     description="Unban a user account (Admin only)",
     *     tags={"Admin - Users"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User unbanned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=2),
     *             @OA\Property(property="name", type="string", example="Publisher User"),
     *             @OA\Property(property="email", type="string", example="publisher@myads.com"),
     *             @OA\Property(
     *                 property="role",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="Publisher")
     *             ),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Admin access required"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function unban(User $user)
    {
        $user = $this->adminService->unbanUser($user);

        return new UserResource($user);
    }
}
