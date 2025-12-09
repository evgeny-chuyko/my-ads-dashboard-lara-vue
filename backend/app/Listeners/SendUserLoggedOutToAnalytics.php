<?php

namespace App\Listeners;

use App\Events\UserLoggedOut;
use App\Services\Analytics\AnalyticsService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserLoggedOutToAnalytics implements ShouldQueue
{
    public function __construct(
        private AnalyticsService $analytics
    ) {}

    public function handle(UserLoggedOut $event): void
    {
        $this->analytics->track('User Logged Out', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'role' => $event->user->role->name,
        ]);
    }
}
