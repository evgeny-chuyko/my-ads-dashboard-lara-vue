<?php

namespace App\Services\Analytics\Contracts;

interface AnalyticsProvider
{
    public function track(string $event, array $properties = []): void;

    public function identify(string $userId, array $traits = []): void;

    public function page(string $name, array $properties = []): void;

    public function isEnabled(): bool;

    public function getName(): string;
}
