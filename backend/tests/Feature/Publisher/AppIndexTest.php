<?php

namespace Tests\Feature\Publisher;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelpers;

class AppIndexTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    public function test_authenticated_user_can_list_their_apps(): void
    {
        $user = $this->createUser();
        $app1 = $this->createApp($user, ['name' => 'App 1']);
        $app2 = $this->createApp($user, ['name' => 'App 2']);

        $response = $this->loginAs($user)
            ->getJson('/api/apps');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['name' => 'App 1'])
            ->assertJsonFragment(['name' => 'App 2']);
    }

    public function test_user_only_sees_their_own_apps(): void
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();

        $app1 = $this->createApp($user1, ['name' => 'User 1 App']);
        $app2 = $this->createApp($user2, ['name' => 'User 2 App']);

        $response = $this->loginAs($user1)
            ->getJson('/api/apps');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'User 1 App'])
            ->assertJsonMissing(['name' => 'User 2 App']);
    }

    public function test_unauthenticated_user_cannot_list_apps(): void
    {
        $response = $this->getJson('/api/apps');

        $this->assertUnauthorized($response);
    }

    public function test_banned_user_cannot_list_apps(): void
    {
        $user = $this->createBannedUser();
        $this->createApp($user);

        $response = $this->loginAs($user)
            ->getJson('/api/apps');

        $this->assertForbidden($response);
    }

    public function test_apps_list_returns_correct_structure(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);

        $response = $this->loginAs($user)
            ->getJson('/api/apps');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'status',
                        'impressions',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    public function test_empty_apps_list_returns_empty_array(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->getJson('/api/apps');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }
}
