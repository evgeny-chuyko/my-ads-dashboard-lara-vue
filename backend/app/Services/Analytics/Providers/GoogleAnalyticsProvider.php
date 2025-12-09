<?php

namespace App\Services\Analytics\Providers;

use App\Services\Analytics\AbstractAnalyticsProvider;
use Illuminate\Support\Facades\Http;

class GoogleAnalyticsProvider extends AbstractAnalyticsProvider
{
    protected string $name = 'Google Analytics';
    private ?string $measurementId;
    private ?string $apiSecret;

    public function __construct()
    {
        $this->measurementId = config('analytics.google.measurement_id');
        $this->apiSecret = config('analytics.google.api_secret');
        $this->enabled = !empty($this->measurementId) && !empty($this->apiSecret);
    }

    public function track(string $event, array $properties = []): void
    {
        if (!$this->shouldSend()) {
            return;
        }

        try {
            Http::post("https://www.google-analytics.com/mp/collect", [
                'measurement_id' => $this->measurementId,
                'api_secret' => $this->apiSecret,
                'events' => [
                    [
                        'name' => $event,
                        'params' => $properties,
                    ],
                ],
            ]);
        } catch (\Throwable $e) {
            $this->logError($e, 'track');
        }
    }

    public function identify(string $userId, array $traits = []): void
    {
        // Google Analytics handles this differently
        // User properties are set with events
    }

    public function page(string $name, array $properties = []): void
    {
        $this->track('page_view', array_merge(['page_title' => $name], $properties));
    }
}
