<?php

namespace App\Listeners;

use App\Events\AppUpdated;
use App\Services\Analytics\AnalyticsService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAppUpdatedToAnalytics implements ShouldQueue
{
    public function __construct(
        private AnalyticsService $analytics
    ) {}

    public function handle(AppUpdated $event): void
    {
        $this->analytics->track('App Updated', [
            'app_id' => $event->app->id,
            'app_name' => $event->app->name,
            'user_id' => $event->app->user_id,
            'status' => $event->app->status->value,
            'changes' => array_keys($event->changes),
            'changed_fields_count' => count($event->changes),
        ]);
    }
}
