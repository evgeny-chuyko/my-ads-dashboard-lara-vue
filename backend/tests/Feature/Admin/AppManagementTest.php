<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelpers;

class AppManagementTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    public function test_admin_can_list_all_apps(): void
    {
        $admin = $this->createAdmin();
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        
        $app1 = $this->createApp($user1, ['name' => 'App 1']);
        $app2 = $this->createApp($user2, ['name' => 'App 2']);

        $response = $this->loginAs($admin)
            ->getJson('/api/admin/apps');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'App 1'])
            ->assertJsonFragment(['name' => 'App 2']);
    }

    public function test_admin_can_see_apps_from_all_users(): void
    {
        $admin = $this->createAdmin();
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        
        $this->createApp($user1);
        $this->createApp($user2);

        $response = $this->loginAs($admin)
            ->getJson('/api/admin/apps');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_non_admin_cannot_list_all_apps(): void
    {
        $publisher = $this->createPublisher();

        $response = $this->loginAs($publisher)
            ->getJson('/api/admin/apps');

        $this->assertForbidden($response);
    }

    public function test_unauthenticated_user_cannot_list_all_apps(): void
    {
        $response = $this->getJson('/api/admin/apps');

        $this->assertUnauthorized($response);
    }

    public function test_admin_apps_list_includes_user_information(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createUser(['name' => 'Test User']);
        $app = $this->createApp($user);

        $response = $this->loginAs($admin)
            ->getJson('/api/admin/apps');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'user',
                    ],
                ],
            ]);
    }

    public function test_banned_admin_cannot_access_apps_list(): void
    {
        $admin = $this->createAdmin();
        $admin->ban();

        $response = $this->loginAs($admin)
            ->getJson('/api/admin/apps');

        $this->assertForbidden($response);
    }
}
