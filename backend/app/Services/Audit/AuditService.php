<?php

namespace App\Services\Audit;

use App\Enums\AuditAction;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AuditService
{
    public function log(
        User $user,
        AuditAction $action,
        string $model,
        ?int $modelId = null,
        ?array $details = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'details' => $details,
        ]);
    }

    public function getRecentLogs(int $limit = 50): Collection
    {
        return AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getUserLogs(User $user, int $limit = 50): Collection
    {
        return AuditLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
