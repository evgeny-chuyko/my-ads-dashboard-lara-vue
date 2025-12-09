<?php

namespace App\Listeners;

use App\Events\AppDeleted;
use App\Services\Analytics\AnalyticsService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAppDeletedToAnalytics implements ShouldQueue
{
    public function __construct(
        private AnalyticsService $analytics
    ) {}

    public function handle(AppDeleted $event): void
    {
        $this->analytics->track('App Deleted', [
            'app_id' => $event->app->id,
            'app_name' => $event->app->name,
            'user_id' => $event->app->user_id,
            'status' => $event->app->status->value,
            'impressions' => $event->app->impressions,
        ]);
    }
}
