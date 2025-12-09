<?php

namespace App\Events;

use App\Models\App;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
class AppDeleted
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public App $app
    ) {}
}
