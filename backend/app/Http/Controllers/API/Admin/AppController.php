<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppResource;
use App\Services\Admin\AdminService;

class AppController extends Controller
{
    public function __construct(
        private AdminService $adminService
    ) {}

    /**
     * @OA\Get(
     *     path="/admin/apps",
     *     summary="Get all applications",
     *     description="Retrieve list of all applications from all users (Admin only)",
     *     tags={"Admin - Apps"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of all applications",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="My App"),
     *                 @OA\Property(property="description", type="string", example="App description"),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="impressions", type="integer", example=1000),
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Publisher User"),
     *                     @OA\Property(property="email", type="string", example="publisher@myads.com")
     *                 ),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Admin access required"
     *     )
     * )
     */
    public function index()
    {
        $apps = $this->adminService->getAllApps();

        return AppResource::collection($apps);
    }
}
