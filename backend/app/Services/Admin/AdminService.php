<?php

namespace App\Services\Admin;

use App\Models\App;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AdminService
{
    public function getAllUsers(): Collection
    {
        return User::with('role')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAllApps(): Collection
    {
        return App::with('user.role')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function banUser(User $user): User
    {
        $user->ban();
        return $user->fresh();
    }

    public function unbanUser(User $user): User
    {
        $user->unban();
        return $user->fresh();
    }

    public function getStatistics(): array
    {
        return [
            'total_users' => User::count(),
            'total_publishers' => User::where('role_id', 2)->count(),
            'total_apps' => App::count(),
            'active_apps' => App::where('status', 'active')->count(),
            'total_impressions' => (int) App::sum('impressions'),
            'banned_users' => User::where('status', 'banned')->count(),
        ];
    }
}
