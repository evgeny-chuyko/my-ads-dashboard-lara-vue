<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelpers;

class MeTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    public function test_authenticated_user_can_get_their_data(): void
    {
        $user = $this->createUser([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response = $this->loginAs($user)
            ->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                ],
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'status',
                    'created_at',
                ],
            ]);
    }

    public function test_unauthenticated_user_cannot_access_me_endpoint(): void
    {
        $response = $this->getJson('/api/auth/me');

        $this->assertUnauthorized($response);
    }

    public function test_me_endpoint_returns_user_role(): void
    {
        $admin = $this->createAdmin();

        $response = $this->loginAs($admin)
            ->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJsonPath('data.role.id', \App\Models\Role::ADMIN);
    }

    public function test_me_endpoint_returns_user_status(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'active');
    }
}
