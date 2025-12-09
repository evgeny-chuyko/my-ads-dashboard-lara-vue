<?php

namespace App\Services\App;

use App\Enums\AppStatus;
use App\Events\{AppCreated, AppDeleted, AppUpdated};
use App\Models\App;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AppService
{
    public function getUserApps(User $user): Collection
    {
        return $user->apps()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createApp(User $user, array $data): App
    {
        $app = $user->apps()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'status' => AppStatus::Active,
            'impressions' => 0,
        ]);

        event(new AppCreated($app));

        return $app;
    }

    public function updateApp(App $app, array $data): App
    {
        $app->update([
            'name' => $data['name'] ?? $app->name,
            'description' => $data['description'] ?? $app->description,
            'status' => $data['status'] ?? $app->status,
        ]);

        $changes = $app->getChanges();
        
        if (!empty($changes)) {
            event(new AppUpdated($app, $changes));
        }

        return $app->fresh();
    }

    public function deleteApp(App $app): void
    {
        event(new AppDeleted($app));
        
        $app->delete();
    }

    public function getAppStats(App $app): array
    {
        return [
            'total_impressions' => $app->impressions,
            'status' => $app->status,
            'created_at' => $app->created_at,
            'days_active' => $app->created_at->diffInDays(now()),
            'avg_daily_impressions' => $app->created_at->diffInDays(now()) > 0
                ? round($app->impressions / $app->created_at->diffInDays(now()))
                : 0,
        ];
    }
}
