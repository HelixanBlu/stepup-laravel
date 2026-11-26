<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\Challenge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $firstChallenge = Challenge::first();

        $badges = [
            [
                'name' => 'First Step',
                'description' => 'Diberikan saat pertama kali menyelesaikan sebuah challenge.',
                'category' => 'challenge',
                'rarity' => 'common',
                'unlock_requirement' => 'Complete 1 challenge',
                'status' => 'active',
            ],
            [
                'name' => 'Step Master',
                'description' => 'Berhasil berjalan 10.000 langkah dari challenge yang diselesaikan.',
                'category' => 'activity',
                'rarity' => 'rare',
                'unlock_requirement' => 'Walk 10,000 steps in completed challenges',
                'status' => 'active',
            ],
            [
                'name' => 'Challenge Veteran',
                'description' => 'Menyelesaikan 10 challenge berbeda.',
                'category' => 'challenge',
                'rarity' => 'epic',
                'unlock_requirement' => 'Complete 10 challenges',
                'status' => 'active',
            ],
            [
                'name' => 'Collection Hunter',
                'description' => 'Menyelesaikan 3 koleksi challenge.',
                'category' => 'collection',
                'rarity' => 'epic',
                'unlock_requirement' => 'Complete 3 collections',
                'status' => 'active',
            ],
            [
                'name' => '7-Day Streak',
                'description' => 'Aktif selama 7 hari berturut-turut.',
                'category' => 'streak',
                'rarity' => 'rare',
                'unlock_requirement' => 'Maintain a 7-Day Streak',
                'status' => 'active',
            ],
            [
                'name' => 'Campus Legend',
                'description' => 'Badge legendaris khusus untuk pengguna paling aktif.',
                'category' => 'special_event',
                'rarity' => 'legendary',
                'unlock_requirement' => 'Complete 10 challenges',
                'status' => 'active',
            ],
        ];

        if ($firstChallenge) {
            $badges[] = [
                'name' => 'Early Bird',
                'description' => 'Menyelesaikan challenge pertama yang tersedia.',
                'category' => 'challenge',
                'rarity' => 'common',
                'unlock_requirement' => 'Complete the first available challenge',
                'related_challenge_id' => $firstChallenge->id,
                'status' => 'active',
            ];
        }

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['name' => $badge['name']], $badge);
        }
    }
}
