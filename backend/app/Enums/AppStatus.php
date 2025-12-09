<?php

namespace App\Enums;

enum AppStatus: string
{
    case Active = 'active';
    case Paused = 'paused';
    case Archived = 'archived';

    public function label(): string
    {
        return match($this) {
            self::Active => 'Active',
            self::Paused => 'Paused',
            self::Archived => 'Archived',
        };
    }

    public function isActive(): bool
    {
        return $this === self::Active;
    }

    public function isPaused(): bool
    {
        return $this === self::Paused;
    }

    public function isArchived(): bool
    {
        return $this === self::Archived;
    }
}
