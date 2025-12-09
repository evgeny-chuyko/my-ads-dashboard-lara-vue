<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelpers;

class LoginTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = $this->createUser([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email', 'role', 'status'],
                'token',
            ]);
    }

    public function test_login_fails_with_invalid_email(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $this->assertUnauthorized($response);
    }

    public function test_login_fails_with_invalid_password(): void
    {
        $user = $this->createUser([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertUnauthorized($response);
    }

    public function test_login_requires_email(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'password' => 'password123',
        ]);

        $this->assertJsonValidationErrors($response, ['email']);
    }

    public function test_login_requires_password(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
        ]);

        $this->assertJsonValidationErrors($response, ['password']);
    }

    public function test_login_returns_access_token(): void
    {
        $user = $this->createUser([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);

        $this->assertNotEmpty($response->json('token'));
    }

    public function test_banned_user_cannot_login(): void
    {
        $user = $this->createBannedUser([
            'email' => 'banned@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'banned@example.com',
            'password' => 'password123',
        ]);

        $this->assertUnauthorized($response);
    }

    public function test_login_returns_user_data(): void
    {
        $user = $this->createUser([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'id' => $user->id,
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                ],
            ]);
    }
}
