<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelpers;

class UserManagementTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    public function test_admin_can_list_all_users(): void
    {
        $admin = $this->createAdmin();
        $user1 = $this->createUser(['name' => 'User 1']);
        $user2 = $this->createUser(['name' => 'User 2']);

        $response = $this->loginAs($admin)
            ->getJson('/api/admin/users');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'User 1'])
            ->assertJsonFragment(['name' => 'User 2']);
    }

    public function test_non_admin_cannot_list_users(): void
    {
        $user = $this->createPublisher();

        $response = $this->loginAs($user)
            ->getJson('/api/admin/users');

        $this->assertForbidden($response);
    }

    public function test_unauthenticated_user_cannot_list_users(): void
    {
        $response = $this->getJson('/api/admin/users');

        $this->assertUnauthorized($response);
    }

    public function test_admin_can_ban_user(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createUser();

        $response = $this->loginAs($admin)
            ->postJson("/api/admin/users/{$user->id}/ban");

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'banned');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'banned',
        ]);
    }

    public function test_admin_can_unban_user(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createBannedUser();

        $response = $this->loginAs($admin)
            ->postJson("/api/admin/users/{$user->id}/unban");

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'active');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'active',
        ]);
    }

    public function test_non_admin_cannot_ban_user(): void
    {
        $publisher = $this->createPublisher();
        $user = $this->createUser();

        $response = $this->loginAs($publisher)
            ->postJson("/api/admin/users/{$user->id}/ban");

        $this->assertForbidden($response);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'active',
        ]);
    }

    public function test_non_admin_cannot_unban_user(): void
    {
        $publisher = $this->createPublisher();
        $user = $this->createBannedUser();

        $response = $this->loginAs($publisher)
            ->postJson("/api/admin/users/{$user->id}/unban");

        $this->assertForbidden($response);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'banned',
        ]);
    }

    public function test_banning_nonexistent_user_returns_404(): void
    {
        $admin = $this->createAdmin();

        $response = $this->loginAs($admin)
            ->postJson('/api/admin/users/99999/ban');

        $this->assertNotFound($response);
    }

    public function test_unbanning_nonexistent_user_returns_404(): void
    {
        $admin = $this->createAdmin();

        $response = $this->loginAs($admin)
            ->postJson('/api/admin/users/99999/unban');

        $this->assertNotFound($response);
    }

    public function test_banned_admin_cannot_access_admin_endpoints(): void
    {
        $admin = $this->createAdmin();
        $admin->ban();

        $response = $this->loginAs($admin)
            ->getJson('/api/admin/users');

        $this->assertForbidden($response);
    }
}
