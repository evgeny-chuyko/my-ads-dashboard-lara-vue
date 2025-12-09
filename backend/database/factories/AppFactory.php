<?php

namespace Database\Factories;

use App\Enums\AppStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\App>
 */
class AppFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'status' => AppStatus::Active,
            'impressions' => fake()->numberBetween(0, 10000),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AppStatus::Active,
        ]);
    }

    public function paused(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AppStatus::Paused,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AppStatus::Archived,
        ]);
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
