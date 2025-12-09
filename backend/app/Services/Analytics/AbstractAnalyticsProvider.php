<?php

namespace App\Services\Analytics;

use App\Services\Analytics\Contracts\AnalyticsProvider;
use Illuminate\Support\Facades\Log;

abstract class AbstractAnalyticsProvider implements AnalyticsProvider
{
    protected bool $enabled = true;
    protected string $name = 'Unknown';

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getName(): string
    {
        return $this->name;
    }

    protected function logError(\Throwable $e, string $action): void
    {
        Log::error("Analytics [{$this->name}] error on {$action}", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }

    protected function shouldSend(): bool
    {
        return $this->isEnabled() && !app()->environment('testing');
    }
}
