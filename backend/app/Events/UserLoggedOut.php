<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
class UserLoggedOut
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public User $user
    ) {}
}
