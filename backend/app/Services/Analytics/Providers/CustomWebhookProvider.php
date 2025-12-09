<?php

namespace App\Services\Analytics\Providers;

use App\Services\Analytics\AbstractAnalyticsProvider;
use Illuminate\Support\Facades\Http;

class CustomWebhookProvider extends AbstractAnalyticsProvider
{
    protected string $name = 'Custom Webhook';
    private ?string $webhookUrl;
    private ?string $secretKey;

    public function __construct()
    {
        $this->webhookUrl = config('analytics.webhook.url');
        $this->secretKey = config('analytics.webhook.secret');
        $this->enabled = !empty($this->webhookUrl);
    }

    public function track(string $event, array $properties = []): void
    {
        if (!$this->shouldSend()) {
            return;
        }

        try {
            $payload = [
                'type' => 'track',
                'event' => $event,
                'properties' => $properties,
                'timestamp' => now()->toIso8601String(),
            ];

            $this->sendWebhook($payload);
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
            $payload = [
                'type' => 'identify',
                'user_id' => $userId,
                'traits' => $traits,
                'timestamp' => now()->toIso8601String(),
            ];

            $this->sendWebhook($payload);
        } catch (\Throwable $e) {
            $this->logError($e, 'identify');
        }
    }

    public function page(string $name, array $properties = []): void
    {
        if (!$this->shouldSend()) {
            return;
        }

        try {
            $payload = [
                'type' => 'page',
                'name' => $name,
                'properties' => $properties,
                'timestamp' => now()->toIso8601String(),
            ];

            $this->sendWebhook($payload);
        } catch (\Throwable $e) {
            $this->logError($e, 'page');
        }
    }

    private function sendWebhook(array $payload): void
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];

        if ($this->secretKey) {
            $headers['X-Webhook-Secret'] = $this->secretKey;
            $headers['X-Webhook-Signature'] = hash_hmac('sha256', json_encode($payload), $this->secretKey);
        }

        Http::withHeaders($headers)
            ->timeout(5)
            ->post($this->webhookUrl, $payload);
    }
}
