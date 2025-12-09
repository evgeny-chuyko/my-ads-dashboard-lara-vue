<?php

namespace App\Providers;

use App\Models\App;
use App\Policies\AppPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        App::class => AppPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
