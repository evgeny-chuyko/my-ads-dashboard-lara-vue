<?php

namespace Tests\Feature\Publisher;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelpers;

class AppCreateTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    public function test_authenticated_user_can_create_app(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->postJson('/api/apps', [
                'name' => 'My New App',
                'description' => 'App description',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'My New App',
                    'description' => 'App description',
                    'status' => 'active',
                ],
            ]);

        $this->assertDatabaseHas('apps', [
            'name' => 'My New App',
            'user_id' => $user->id,
        ]);
    }

    public function test_created_app_has_default_status_active(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->postJson('/api/apps', [
                'name' => 'Test App',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.status', 'active');
    }

    public function test_created_app_has_zero_impressions(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->postJson('/api/apps', [
                'name' => 'Test App',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.impressions', 0);
    }

    public function test_app_creation_requires_name(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->postJson('/api/apps', [
                'description' => 'App description',
            ]);

        $this->assertJsonValidationErrors($response, ['name']);
    }

    public function test_app_name_must_be_string(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->postJson('/api/apps', [
                'name' => 12345,
            ]);

        $this->assertJsonValidationErrors($response, ['name']);
    }

    public function test_app_name_cannot_exceed_255_characters(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->postJson('/api/apps', [
                'name' => str_repeat('a', 256),
            ]);

        $this->assertJsonValidationErrors($response, ['name']);
    }

    public function test_app_description_is_optional(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->postJson('/api/apps', [
                'name' => 'Test App',
            ]);

        $response->assertStatus(201);
    }

    public function test_app_description_can_be_null(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->postJson('/api/apps', [
                'name' => 'Test App',
                'description' => null,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.description', null);
    }

    public function test_app_description_cannot_exceed_1000_characters(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->postJson('/api/apps', [
                'name' => 'Test App',
                'description' => str_repeat('a', 1001),
            ]);

        $this->assertJsonValidationErrors($response, ['description']);
    }

    public function test_unauthenticated_user_cannot_create_app(): void
    {
        $response = $this->postJson('/api/apps', [
            'name' => 'Test App',
        ]);

        $this->assertUnauthorized($response);
    }

    public function test_banned_user_cannot_create_app(): void
    {
        $user = $this->createBannedUser();

        $response = $this->loginAs($user)
            ->postJson('/api/apps', [
                'name' => 'Test App',
            ]);

        $this->assertForbidden($response);
    }

    public function test_created_app_belongs_to_authenticated_user(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->postJson('/api/apps', [
                'name' => 'Test App',
            ]);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('apps', [
            'name' => 'Test App',
            'user_id' => $user->id,
        ]);
    }

    public function test_created_app_returns_correct_structure(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->postJson('/api/apps', [
                'name' => 'Test App',
                'description' => 'Description',
            ]);

        $response->assertStatus(201)
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
}
