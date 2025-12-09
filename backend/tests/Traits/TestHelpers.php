<?php

namespace Tests\Traits;

use App\Models\App;
use App\Models\User;
use Illuminate\Testing\TestResponse;

trait TestHelpers
{
    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    protected function createAdmin(array $attributes = []): User
    {
        return User::factory()->admin()->create($attributes);
    }

    protected function createPublisher(array $attributes = []): User
    {
        return User::factory()->publisher()->create($attributes);
    }

    protected function createBannedUser(array $attributes = []): User
    {
        return User::factory()->banned()->create($attributes);
    }

    protected function createApp(User $user = null, array $attributes = []): App
    {
        if ($user) {
            return App::factory()->forUser($user)->create($attributes);
        }

        return App::factory()->create($attributes);
    }

    protected function loginAs(User $user): self
    {
        $this->actingAs($user, 'sanctum');
        return $this;
    }

    protected function assertJsonValidationErrors(TestResponse $response, array $keys): void
    {
        $response->assertStatus(422);
        $response->assertJsonValidationErrors($keys);
    }

    protected function assertUnauthorized(TestResponse $response): void
    {
        $response->assertStatus(401);
    }

    protected function assertForbidden(TestResponse $response): void
    {
        $response->assertStatus(403);
    }

    protected function assertNotFound(TestResponse $response): void
    {
        $response->assertStatus(404);
    }
}
