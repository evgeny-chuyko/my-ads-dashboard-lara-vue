<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditLogResource;
use App\Services\Audit\AuditService;

class AuditLogController extends Controller
{
    public function __construct(
        private AuditService $auditService
    ) {}

    /**
     * @OA\Get(
     *     path="/admin/audit-logs",
     *     summary="Get audit logs",
     *     description="Retrieve recent audit logs (Admin only)",
     *     tags={"Admin - Audit"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of audit logs",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(property="action", type="string", example="created", enum={"created", "updated", "deleted", "banned", "unbanned"}),
     *                 @OA\Property(property="model", type="string", example="App", description="Model type (App, User, etc.)"),
     *                 @OA\Property(property="model_id", type="integer", example=5, description="ID of the affected model"),
     *                 @OA\Property(property="details", type="string", example="Created new app: My App", description="Action details"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Publisher User"),
     *                     @OA\Property(property="email", type="string", example="publisher@myads.com")
     *                 )
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
        $logs = $this->auditService->getRecentLogs(100);

        return AuditLogResource::collection($logs);
    }
}
