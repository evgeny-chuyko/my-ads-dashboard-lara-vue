<?php

namespace Tests\Feature\Publisher;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelpers;

class AppDeleteTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    public function test_user_can_delete_their_own_app(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);

        $response = $this->loginAs($user)
            ->deleteJson("/api/apps/{$app->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'App deleted successfully']);

        $this->assertDatabaseMissing('apps', [
            'id' => $app->id,
        ]);
    }

    public function test_user_cannot_delete_another_users_app(): void
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $app = $this->createApp($user2);

        $response = $this->loginAs($user1)
            ->deleteJson("/api/apps/{$app->id}");

        $this->assertForbidden($response);

        $this->assertDatabaseHas('apps', [
            'id' => $app->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_delete_app(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);

        $response = $this->deleteJson("/api/apps/{$app->id}");

        $this->assertUnauthorized($response);

        $this->assertDatabaseHas('apps', [
            'id' => $app->id,
        ]);
    }

    public function test_banned_user_cannot_delete_app(): void
    {
        $user = $this->createBannedUser();
        $app = $this->createApp($user);

        $response = $this->loginAs($user)
            ->deleteJson("/api/apps/{$app->id}");

        $this->assertForbidden($response);

        $this->assertDatabaseHas('apps', [
            'id' => $app->id,
        ]);
    }

    public function test_deleting_nonexistent_app_returns_404(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->deleteJson('/api/apps/99999');

        $this->assertNotFound($response);
    }

    public function test_delete_returns_success_message(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);

        $response = $this->loginAs($user)
            ->deleteJson("/api/apps/{$app->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }
}
