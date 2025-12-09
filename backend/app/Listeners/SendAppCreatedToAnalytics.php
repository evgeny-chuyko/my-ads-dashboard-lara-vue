<?php

namespace App\Listeners;

use App\Events\AppCreated;
use App\Services\Analytics\AnalyticsService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAppCreatedToAnalytics implements ShouldQueue
{
    public function __construct(
        private AnalyticsService $analytics
    ) {}

    public function handle(AppCreated $event): void
    {
        $this->analytics->track('App Created', [
            'app_id' => $event->app->id,
            'app_name' => $event->app->name,
            'user_id' => $event->app->user_id,
            'status' => $event->app->status->value,
            'has_description' => !empty($event->app->description),
        ]);
    }
}
