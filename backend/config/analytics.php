<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Analytics Providers
    |--------------------------------------------------------------------------
    |
    | List of analytics providers to use. Each provider will receive events.
    | Providers are loaded automatically if their credentials are configured.
    |
    */

    'providers' => [
        \App\Services\Analytics\Providers\GoogleAnalyticsProvider::class,
        \App\Services\Analytics\Providers\MixpanelProvider::class,
        \App\Services\Analytics\Providers\CustomWebhookProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Analytics Configuration
    |--------------------------------------------------------------------------
    */

    'google' => [
        'measurement_id' => env('GOOGLE_ANALYTICS_MEASUREMENT_ID'),
        'api_secret' => env('GOOGLE_ANALYTICS_API_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Mixpanel Configuration
    |--------------------------------------------------------------------------
    */

    'mixpanel' => [
        'token' => env('MIXPANEL_TOKEN'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Webhook Configuration
    |--------------------------------------------------------------------------
    */

    'webhook' => [
        'url' => env('ANALYTICS_WEBHOOK_URL'),
        'secret' => env('ANALYTICS_WEBHOOK_SECRET'),
    ],
];
