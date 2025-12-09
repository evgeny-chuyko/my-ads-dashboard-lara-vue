<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Traits\TestHelpers;

class LogoutTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    public function test_authenticated_user_can_logout(): void
    {
        $user = $this->createUser();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);
    }

    public function test_logout_deletes_current_access_token(): void
    {
        $user = $this->createUser();
        $token = $user->createToken('test-token');

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'test-token',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->postJson('/api/auth/logout');

        $response->assertStatus(200);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_logout(): void
    {
        $response = $this->postJson('/api/auth/logout');

        $this->assertUnauthorized($response);
    }

    public function test_logout_with_invalid_token_fails(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer invalid-token')
            ->postJson('/api/auth/logout');

        $this->assertUnauthorized($response);
    }
}
