<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RewardSeeder::class,
            ChallengeSeeder::class,
            CollectionSeeder::class,
            BadgeSeeder::class,
            CommunitySeeder::class,
        ]);
    }
}
