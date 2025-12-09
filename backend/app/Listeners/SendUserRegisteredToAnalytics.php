<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Services\Analytics\AnalyticsService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserRegisteredToAnalytics implements ShouldQueue
{
    public function __construct(
        private AnalyticsService $analytics
    ) {}

    public function handle(UserRegistered $event): void
    {
        // Identify user in analytics
        $this->analytics->identify((string) $event->user->id, [
            'name' => $event->user->name,
            'email' => $event->user->email,
            'role' => $event->user->role->name,
            'status' => $event->user->status->value,
            'created_at' => $event->user->created_at->toIso8601String(),
        ]);

        // Track registration event
        $this->analytics->track('User Registered', [
            'user_id' => $event->user->id,
            'role' => $event->user->role->name,
        ]);
    }
}
