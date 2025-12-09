<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
class UserRegistered
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public User $user
    ) {}
}
