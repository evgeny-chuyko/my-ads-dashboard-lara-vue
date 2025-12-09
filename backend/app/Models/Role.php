<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    public const ADMIN = 1;
    public const PUBLISHER = 2;

    protected $fillable = [
        'name',
        'description',
    ];

    public $timestamps = false;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function isAdmin(): bool
    {
        return $this->id === self::ADMIN;
    }

    public function isPublisher(): bool
    {
        return $this->id === self::PUBLISHER;
    }
}
