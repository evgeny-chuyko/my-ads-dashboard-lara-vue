<?php

namespace Tests\Feature\Publisher;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelpers;

class AppStatsTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    public function test_user_can_view_their_app_stats(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user, ['impressions' => 5000]);

        $response = $this->loginAs($user)
            ->getJson("/api/apps/{$app->id}/stats");

        $response->assertStatus(200);
    }

    public function test_user_cannot_view_another_users_app_stats(): void
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $app = $this->createApp($user2);

        $response = $this->loginAs($user1)
            ->getJson("/api/apps/{$app->id}/stats");

        $this->assertForbidden($response);
    }

    public function test_unauthenticated_user_cannot_view_app_stats(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);

        $response = $this->getJson("/api/apps/{$app->id}/stats");

        $this->assertUnauthorized($response);
    }

    public function test_banned_user_cannot_view_app_stats(): void
    {
        $user = $this->createBannedUser();
        $app = $this->createApp($user);

        $response = $this->loginAs($user)
            ->getJson("/api/apps/{$app->id}/stats");

        $this->assertForbidden($response);
    }

    public function test_viewing_stats_for_nonexistent_app_returns_404(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->getJson('/api/apps/99999/stats');

        $this->assertNotFound($response);
    }
}
