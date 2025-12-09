<?php

namespace Database\Seeders;

use App\Enums\AppStatus;
use App\Models\App;
use App\Models\User;
use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    public function run(): void
    {
        $publisher1 = User::where('email', 'publisher@myads.com')->first();
        $publisher2 = User::where('email', 'publisher2@myads.com')->first();

        if (!$publisher1 || !$publisher2) {
            $this->command->warn('Publishers not found. Run UserSeeder first.');
            return;
        }

        $apps = [
            [
                'user_id' => $publisher1->id,
                'name' => 'Mobile Game App',
                'description' => 'Popular mobile game with in-app ads',
                'status' => AppStatus::Active,
                'impressions' => rand(10000, 50000),
            ],
            [
                'user_id' => $publisher1->id,
                'name' => 'News Reader',
                'description' => 'Daily news aggregator application',
                'status' => AppStatus::Active,
                'impressions' => rand(5000, 25000),
            ],
            [
                'user_id' => $publisher1->id,
                'name' => 'Fitness Tracker',
                'description' => 'Health and fitness tracking app',
                'status' => AppStatus::Paused,
                'impressions' => rand(1000, 10000),
            ],
            [
                'user_id' => $publisher2->id,
                'name' => 'Weather App',
                'description' => 'Real-time weather forecasts',
                'status' => AppStatus::Active,
                'impressions' => rand(15000, 40000),
            ],
            [
                'user_id' => $publisher2->id,
                'name' => 'Recipe Book',
                'description' => 'Cooking recipes and meal planning',
                'status' => AppStatus::Active,
                'impressions' => rand(8000, 30000),
            ],
        ];

        foreach ($apps as $appData) {
            App::updateOrCreate(
                [
                    'user_id' => $appData['user_id'],
                    'name' => $appData['name'],
                ],
                $appData
            );
        }
    }
}
