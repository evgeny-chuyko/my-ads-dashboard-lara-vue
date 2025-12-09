<?php

namespace App\Providers;

use App\Events\{AppCreated, AppDeleted, AppUpdated, UserLoggedIn, UserLoggedOut, UserRegistered};
use App\Listeners\{
    SendAppCreatedToAnalytics,
    SendAppDeletedToAnalytics,
    SendAppUpdatedToAnalytics,
    SendUserLoggedInToAnalytics,
    SendUserLoggedOutToAnalytics,
    SendUserRegisteredToAnalytics
};
use App\Services\Analytics\AnalyticsService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AnalyticsService::class, function ($app) {
            return new AnalyticsService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(AppCreated::class, SendAppCreatedToAnalytics::class);
        Event::listen(AppUpdated::class, SendAppUpdatedToAnalytics::class);
        Event::listen(AppDeleted::class, SendAppDeletedToAnalytics::class);
        Event::listen(UserRegistered::class, SendUserRegisteredToAnalytics::class);
        Event::listen(UserLoggedIn::class, SendUserLoggedInToAnalytics::class);
        Event::listen(UserLoggedOut::class, SendUserLoggedOutToAnalytics::class);
    }
}
