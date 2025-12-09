<?php

namespace App\Services\Analytics\Providers;

use App\Services\Analytics\AbstractAnalyticsProvider;
use Illuminate\Support\Facades\Http;

class MixpanelProvider extends AbstractAnalyticsProvider
{
    protected string $name = 'Mixpanel';
    private ?string $token;

    public function __construct()
    {
        $this->token = config('analytics.mixpanel.token');
        $this->enabled = !empty($this->token);
    }

    public function track(string $event, array $properties = []): void
    {
        if (!$this->shouldSend()) {
            return;
        }

        try {
            $data = [
                'event' => $event,
                'properties' => array_merge([
                    'token' => $this->token,
                    'time' => time(),
                ], $properties),
            ];

            Http::post('https://api.mixpanel.com/track', [
                'data' => base64_encode(json_encode($data)),
            ]);
        } catch (\Throwable $e) {
            $this->logError($e, 'track');
        }
    }

    public function identify(string $userId, array $traits = []): void
    {
        if (!$this->shouldSend()) {
            return;
        }

        try {
            $data = [
                '$token' => $this->token,
                '$distinct_id' => $userId,
                '$set' => $traits,
            ];

            Http::post('https://api.mixpanel.com/engage', [
                'data' => base64_encode(json_encode($data)),
            ]);
        } catch (\Throwable $e) {
            $this->logError($e, 'identify');
        }
    }

    public function page(string $name, array $properties = []): void
    {
        $this->track('Page View', array_merge(['page' => $name], $properties));
    }
}
