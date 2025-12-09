<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'id' => Role::ADMIN,
                'name' => 'Admin',
                'description' => 'Administrator with full access',
            ],
            [
                'id' => Role::PUBLISHER,
                'name' => 'Publisher',
                'description' => 'Publisher can manage their own apps',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['id' => $role['id']],
                $role
            );
        }
    }
}
