<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelpers;

class StatsTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    public function test_admin_can_view_stats(): void
    {
        $admin = $this->createAdmin();

        $response = $this->loginAs($admin)
            ->getJson('/api/admin/stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_users',
                'total_publishers',
                'total_apps',
                'active_apps',
                'total_impressions',
                'banned_users',
            ]);
    }

    public function test_non_admin_cannot_view_stats(): void
    {
        $publisher = $this->createPublisher();

        $response = $this->loginAs($publisher)
            ->getJson('/api/admin/stats');

        $this->assertForbidden($response);
    }

    public function test_unauthenticated_user_cannot_view_stats(): void
    {
        $response = $this->getJson('/api/admin/stats');

        $this->assertUnauthorized($response);
    }

    public function test_stats_show_correct_user_counts(): void
    {
        $admin = $this->createAdmin();
        $this->createUser();
        $this->createUser();
        $this->createBannedUser();

        $response = $this->loginAs($admin)
            ->getJson('/api/admin/stats');

        $response->assertStatus(200)
            ->assertJsonPath('total_users', 4)
            ->assertJsonPath('total_publishers', 3)
            ->assertJsonPath('banned_users', 1);
    }

    public function test_stats_show_correct_app_counts(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createUser();
        
        $this->createApp($user);
        $this->createApp($user);

        $response = $this->loginAs($admin)
            ->getJson('/api/admin/stats');

        $response->assertStatus(200)
            ->assertJsonPath('total_apps', 2);
    }

    public function test_stats_show_total_impressions(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createUser();
        
        $this->createApp($user, ['impressions' => 1000]);
        $this->createApp($user, ['impressions' => 2000]);

        $response = $this->loginAs($admin)
            ->getJson('/api/admin/stats');

        $response->assertStatus(200)
            ->assertJsonPath('total_impressions', 3000);
    }

    public function test_banned_admin_cannot_view_stats(): void
    {
        $admin = $this->createAdmin();
        $admin->ban();

        $response = $this->loginAs($admin)
            ->getJson('/api/admin/stats');

        $this->assertForbidden($response);
    }
}
