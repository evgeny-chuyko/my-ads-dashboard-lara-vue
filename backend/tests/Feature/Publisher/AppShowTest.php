<?php

namespace Tests\Feature\Publisher;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelpers;

class AppShowTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    public function test_user_can_view_their_own_app(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user, [
            'name' => 'My App',
            'description' => 'My Description',
        ]);

        $response = $this->loginAs($user)
            ->getJson("/api/apps/{$app->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $app->id,
                    'name' => 'My App',
                    'description' => 'My Description',
                ],
            ]);
    }

    public function test_user_cannot_view_another_users_app(): void
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $app = $this->createApp($user2);

        $response = $this->loginAs($user1)
            ->getJson("/api/apps/{$app->id}");

        $this->assertForbidden($response);
    }

    public function test_unauthenticated_user_cannot_view_app(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);

        $response = $this->getJson("/api/apps/{$app->id}");

        $this->assertUnauthorized($response);
    }

    public function test_banned_user_cannot_view_app(): void
    {
        $user = $this->createBannedUser();
        $app = $this->createApp($user);

        $response = $this->loginAs($user)
            ->getJson("/api/apps/{$app->id}");

        $this->assertForbidden($response);
    }

    public function test_viewing_nonexistent_app_returns_404(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->getJson('/api/apps/99999');

        $this->assertNotFound($response);
    }

    public function test_app_show_returns_correct_structure(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);

        $response = $this->loginAs($user)
            ->getJson("/api/apps/{$app->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'status',
                    'impressions',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function test_app_show_returns_all_app_data(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user, [
            'name' => 'Test App',
            'description' => 'Test Description',
            'impressions' => 1000,
        ]);

        $response = $this->loginAs($user)
            ->getJson("/api/apps/{$app->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.impressions', 1000)
            ->assertJsonPath('data.status', 'active');
    }
}
