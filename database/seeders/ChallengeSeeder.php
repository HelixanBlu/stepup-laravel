<?php

namespace Database\Seeders;

use App\Models\Challenge;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    public function run(): void
    {
        $challenges = [
            [
                'title' => '10K Steps Daily',
                'description' => 'Capai 10.000 langkah setiap hari selama seminggu penuh untuk memenangkan poin bonus.',
                'challenge_type' => 'daily',
                'goal_type' => 'steps',
                'goal_value' => 10000,
                'reward_points' => 100,
                'badge_reward' => 'Step Master',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(7)->toDateString(),
                'status' => 'active',
            ],
            [
                'title' => 'Weekly 5K Run',
                'description' => 'Lari sejauh 5 kilometer dalam satu minggu. Cocok untuk pemula.',
                'challenge_type' => 'weekly',
                'goal_type' => 'distance',
                'goal_value' => 5,
                'reward_points' => 200,
                'badge_reward' => 'Runner',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(7)->toDateString(),
                'status' => 'active',
            ],
            [
                'title' => 'Active Minutes Marathon',
                'description' => 'Kumpulkan 300 menit aktivitas fisik dalam sebulan.',
                'challenge_type' => 'monthly',
                'goal_type' => 'active_minutes',
                'goal_value' => 300,
                'reward_points' => 500,
                'badge_reward' => 'Iron Will',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(),
                'status' => 'active',
            ],
            [
                'title' => 'Team Walk Challenge',
                'description' => 'Bergabung bersama tim dan kumpulkan 50.000 langkah bersama-sama.',
                'challenge_type' => 'team',
                'goal_type' => 'steps',
                'goal_value' => 50000,
                'reward_points' => 750,
                'badge_reward' => 'Team Player',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
                'status' => 'active',
            ],
            [
                'title' => 'Campus Fitness Event',
                'description' => 'Event khusus kampus - jadilah yang pertama menyelesaikan tantangan lari 10K.',
                'challenge_type' => 'event',
                'goal_type' => 'distance',
                'goal_value' => 10,
                'reward_points' => 1000,
                'badge_reward' => 'Campus Champion',
                'start_date' => now()->addDays(3)->toDateString(),
                'end_date' => now()->addDays(10)->toDateString(),
                'status' => 'draft',
            ],
        ];

        foreach ($challenges as $challenge) {
            Challenge::create($challenge);
        }
    }
}
