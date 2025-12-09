<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
class UserLoggedIn
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public User $user,
        public string $ipAddress,
        public string $userAgent
    ) {}
}
