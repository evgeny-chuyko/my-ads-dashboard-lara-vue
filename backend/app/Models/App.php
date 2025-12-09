<?php

namespace App\Models;

use App\Enums\AppStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class App extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
        'impressions',
    ];

    protected function casts(): array
    {
        return [
            'status' => AppStatus::class,
            'impressions' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function belongsToUser(int $userId): bool
    {
        return $this->user_id === $userId;
    }

    public function incrementImpressions(int $count = 1): void
    {
        $this->increment('impressions', $count);
    }

    public function activate(): void
    {
        $this->update(['status' => AppStatus::Active]);
    }

    public function pause(): void
    {
        $this->update(['status' => AppStatus::Paused]);
    }

    public function archive(): void
    {
        $this->update(['status' => AppStatus::Archived]);
    }
}
