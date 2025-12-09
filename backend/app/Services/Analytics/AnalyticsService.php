<?php

namespace App\Services\Analytics;

use App\Services\Analytics\Contracts\AnalyticsProvider;
use Illuminate\Support\Facades\Log;

class AnalyticsService
{
    private array $providers = [];

    public function __construct()
    {
        $this->registerProviders();
    }

    public function track(string $event, array $properties = []): void
    {
        foreach ($this->providers as $provider) {
            if ($provider->isEnabled()) {
                try {
                    $provider->track($event, $properties);
                } catch (\Throwable $e) {
                    Log::error("Failed to track event to {$provider->getName()}", [
                        'event' => $event,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    public function identify(string $userId, array $traits = []): void
    {
        foreach ($this->providers as $provider) {
            if ($provider->isEnabled()) {
                try {
                    $provider->identify($userId, $traits);
                } catch (\Throwable $e) {
                    Log::error("Failed to identify user to {$provider->getName()}", [
                        'user_id' => $userId,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    public function page(string $name, array $properties = []): void
    {
        foreach ($this->providers as $provider) {
            if ($provider->isEnabled()) {
                try {
                    $provider->page($name, $properties);
                } catch (\Throwable $e) {
                    Log::error("Failed to track page to {$provider->getName()}", [
                        'page' => $name,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    public function addProvider(AnalyticsProvider $provider): self
    {
        $this->providers[] = $provider;
        return $this;
    }

    public function getProviders(): array
    {
        return $this->providers;
    }

    public function getEnabledProviders(): array
    {
        return array_filter($this->providers, fn($p) => $p->isEnabled());
    }

    private function registerProviders(): void
    {
        $providerClasses = config('analytics.providers', []);

        foreach ($providerClasses as $providerClass) {
            if (class_exists($providerClass)) {
                $this->addProvider(app($providerClass));
            }
        }
    }
}
