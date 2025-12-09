<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminService;

class StatsController extends Controller
{
    public function __construct(
        private AdminService $adminService
    ) {}

    /**
     * @OA\Get(
     *     path="/admin/stats",
     *     summary="Get global statistics",
     *     description="Retrieve global platform statistics (Admin only)",
     *     tags={"Admin - Stats"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Global platform statistics",
     *         @OA\JsonContent(
     *             @OA\Property(property="total_users", type="integer", example=10, description="Total number of users"),
     *             @OA\Property(property="total_publishers", type="integer", example=8, description="Total number of publishers"),
     *             @OA\Property(property="total_apps", type="integer", example=25, description="Total number of applications"),
     *             @OA\Property(property="active_apps", type="integer", example=20, description="Number of active applications"),
     *             @OA\Property(property="total_impressions", type="string", example="150000", description="Total impressions across all apps"),
     *             @OA\Property(property="banned_users", type="integer", example=2, description="Number of banned users")
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
        $stats = $this->adminService->getStatistics();

        return response()->json($stats);
    }
}
