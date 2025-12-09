<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelpers;

class AuditLogTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    public function test_admin_can_view_audit_logs(): void
    {
        $admin = $this->createAdmin();

        $response = $this->loginAs($admin)
            ->getJson('/api/admin/audit-logs');

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_view_audit_logs(): void
    {
        $publisher = $this->createPublisher();

        $response = $this->loginAs($publisher)
            ->getJson('/api/admin/audit-logs');

        $this->assertForbidden($response);
    }

    public function test_unauthenticated_user_cannot_view_audit_logs(): void
    {
        $response = $this->getJson('/api/admin/audit-logs');

        $this->assertUnauthorized($response);
    }

    public function test_banned_admin_cannot_view_audit_logs(): void
    {
        $admin = $this->createAdmin();
        $admin->ban();

        $response = $this->loginAs($admin)
            ->getJson('/api/admin/audit-logs');

        $this->assertForbidden($response);
    }

    public function test_audit_logs_return_correct_structure(): void
    {
        $admin = $this->createAdmin();

        $response = $this->loginAs($admin)
            ->getJson('/api/admin/audit-logs');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [],
            ]);
    }
}
