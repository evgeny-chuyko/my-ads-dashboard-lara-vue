<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelpers;

class RegisterTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    public function test_user_can_register_with_valid_data(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email', 'role', 'status'],
                'token',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }

    public function test_registration_requires_name(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertJsonValidationErrors($response, ['name']);
    }

    public function test_registration_requires_email(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertJsonValidationErrors($response, ['email']);
    }

    public function test_registration_requires_valid_email(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertJsonValidationErrors($response, ['email']);
    }

    public function test_registration_requires_unique_email(): void
    {
        $existingUser = $this->createUser(['email' => 'test@example.com']);

        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertJsonValidationErrors($response, ['email']);
    }

    public function test_registration_requires_password(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertJsonValidationErrors($response, ['password']);
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertJsonValidationErrors($response, ['password']);
    }

    public function test_registration_requires_matching_password_confirmation(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ]);

        $this->assertJsonValidationErrors($response, ['password']);
    }

    public function test_registered_user_has_publisher_role_by_default(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue($user->isPublisher());
    }

    public function test_registered_user_receives_access_token(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['token']);

        $this->assertNotEmpty($response->json('token'));
    }
}
