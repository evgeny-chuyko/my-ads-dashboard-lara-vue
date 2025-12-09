<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Services\Analytics\AnalyticsService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserLoggedInToAnalytics implements ShouldQueue
{
    public function __construct(
        private AnalyticsService $analytics
    ) {}

    public function handle(UserLoggedIn $event): void
    {
        $this->analytics->track('User Logged In', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'role' => $event->user->role->name,
            'status' => $event->user->status->value,
            'ip_address' => $event->ipAddress,
            'user_agent' => $event->userAgent,
        ]);
    }
}
