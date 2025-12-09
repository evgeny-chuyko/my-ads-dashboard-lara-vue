<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@myads.com',
                'password' => 'password',
                'role_id' => Role::ADMIN,
                'status' => UserStatus::Active,
            ],
            [
                'name' => 'Publisher One',
                'email' => 'publisher@myads.com',
                'password' => 'password',
                'role_id' => Role::PUBLISHER,
                'status' => UserStatus::Active,
            ],
            [
                'name' => 'Publisher Two',
                'email' => 'publisher2@myads.com',
                'password' => 'password',
                'role_id' => Role::PUBLISHER,
                'status' => UserStatus::Active,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
