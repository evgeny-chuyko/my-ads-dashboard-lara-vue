<?php

namespace Tests\Feature\Publisher;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelpers;

class AppUpdateTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    public function test_user_can_update_their_own_app(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user, [
            'name' => 'Old Name',
            'description' => 'Old Description',
        ]);

        $response = $this->loginAs($user)
            ->putJson("/api/apps/{$app->id}", [
                'name' => 'New Name',
                'description' => 'New Description',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $app->id,
                    'name' => 'New Name',
                    'description' => 'New Description',
                ],
            ]);

        $this->assertDatabaseHas('apps', [
            'id' => $app->id,
            'name' => 'New Name',
            'description' => 'New Description',
        ]);
    }

    public function test_user_can_update_app_name_only(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user, [
            'name' => 'Old Name',
            'description' => 'Description',
        ]);

        $response = $this->loginAs($user)
            ->putJson("/api/apps/{$app->id}", [
                'name' => 'New Name',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'New Name')
            ->assertJsonPath('data.description', 'Description');
    }

    public function test_user_can_update_app_description_only(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user, [
            'name' => 'App Name',
            'description' => 'Old Description',
        ]);

        $response = $this->loginAs($user)
            ->putJson("/api/apps/{$app->id}", [
                'description' => 'New Description',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'App Name')
            ->assertJsonPath('data.description', 'New Description');
    }

    public function test_user_can_update_app_status(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);

        $response = $this->loginAs($user)
            ->putJson("/api/apps/{$app->id}", [
                'status' => 'paused',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'paused');

        $this->assertDatabaseHas('apps', [
            'id' => $app->id,
            'status' => 'paused',
        ]);
    }

    public function test_user_can_set_status_to_archived(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);

        $response = $this->loginAs($user)
            ->putJson("/api/apps/{$app->id}", [
                'status' => 'archived',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'archived');
    }

    public function test_status_must_be_valid_value(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);

        $response = $this->loginAs($user)
            ->putJson("/api/apps/{$app->id}", [
                'status' => 'invalid-status',
            ]);

        $this->assertJsonValidationErrors($response, ['status']);
    }

    public function test_user_cannot_update_another_users_app(): void
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $app = $this->createApp($user2);

        $response = $this->loginAs($user1)
            ->putJson("/api/apps/{$app->id}", [
                'name' => 'Hacked Name',
            ]);

        $this->assertForbidden($response);

        $this->assertDatabaseMissing('apps', [
            'id' => $app->id,
            'name' => 'Hacked Name',
        ]);
    }

    public function test_unauthenticated_user_cannot_update_app(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);

        $response = $this->putJson("/api/apps/{$app->id}", [
            'name' => 'New Name',
        ]);

        $this->assertUnauthorized($response);
    }

    public function test_banned_user_cannot_update_app(): void
    {
        $user = $this->createBannedUser();
        $app = $this->createApp($user);

        $response = $this->loginAs($user)
            ->putJson("/api/apps/{$app->id}", [
                'name' => 'New Name',
            ]);

        $this->assertForbidden($response);
    }

    public function test_app_name_cannot_exceed_255_characters(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);

        $response = $this->loginAs($user)
            ->putJson("/api/apps/{$app->id}", [
                'name' => str_repeat('a', 256),
            ]);

        $this->assertJsonValidationErrors($response, ['name']);
    }

    public function test_app_description_cannot_exceed_1000_characters(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);

        $response = $this->loginAs($user)
            ->putJson("/api/apps/{$app->id}", [
                'description' => str_repeat('a', 1001),
            ]);

        $this->assertJsonValidationErrors($response, ['description']);
    }

    public function test_updating_nonexistent_app_returns_404(): void
    {
        $user = $this->createUser();

        $response = $this->loginAs($user)
            ->putJson('/api/apps/99999', [
                'name' => 'New Name',
            ]);

        $this->assertNotFound($response);
    }

    public function test_update_returns_correct_structure(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);

        $response = $this->loginAs($user)
            ->putJson("/api/apps/{$app->id}", [
                'name' => 'Updated Name',
            ]);

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

    public function test_update_does_not_change_user_id(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user);
        $originalUserId = $app->user_id;

        $response = $this->loginAs($user)
            ->putJson("/api/apps/{$app->id}", [
                'name' => 'Updated Name',
            ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('apps', [
            'id' => $app->id,
            'user_id' => $originalUserId,
        ]);
    }

    public function test_update_does_not_change_impressions(): void
    {
        $user = $this->createUser();
        $app = $this->createApp($user, ['impressions' => 5000]);

        $response = $this->loginAs($user)
            ->putJson("/api/apps/{$app->id}", [
                'name' => 'Updated Name',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.impressions', 5000);
    }
}
