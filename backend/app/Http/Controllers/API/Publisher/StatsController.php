<?php

namespace App\Http\Controllers\API\Publisher;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Services\App\AppService;

class StatsController extends Controller
{
    public function __construct(
        private AppService $appService
    ) {}

    /**
     * @OA\Get(
     *     path="/apps/{id}/stats",
     *     summary="Get app statistics",
     *     description="Retrieve statistics for a specific application",
     *     tags={"Apps"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Application ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Application statistics",
     *         @OA\JsonContent(
     *             @OA\Property(property="total_impressions", type="integer", example=1500),
     *             @OA\Property(property="today_impressions", type="integer", example=150),
     *             @OA\Property(property="status", type="string", example="active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Not the app owner"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Application not found"
     *     )
     * )
     */
    public function show(App $app)
    {
        $this->authorize('view', $app);

        $stats = $this->appService->getAppStats($app);

        return response()->json($stats);
    }
}
