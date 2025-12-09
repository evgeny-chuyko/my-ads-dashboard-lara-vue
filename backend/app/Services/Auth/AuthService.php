<?php

namespace App\Services\Auth;

use App\Enums\UserStatus;
use App\Events\UserRegistered;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => $data['role_id'] ?? Role::PUBLISHER,
            'status' => UserStatus::Active,
        ]);

        event(new UserRegistered($user->load('role')));

        return $user;
    }

    public function login(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            throw new AuthenticationException('Invalid credentials');
        }

        $user = Auth::user();

        if ($user->isBanned()) {
            Auth::logout();
            throw new AuthenticationException('Your account has been banned');
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user->load('role'),
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function me(User $user): User
    {
        return $user->load('role');
    }
}
