<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\Collection;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstCollection = Collection::first();

        $badges = [
            [
                'name' => 'First Step Pioneer',
                'description' => 'Mencatat aktivitas langkah pertama kali di aplikasi StepUp.',
                'badge_icon' => 'badge-first-step.png',
                'category' => 'activity',
                'rarity' => 'common',
                'unlock_requirement' => 'Selesaikan minimal 1 aktivitas jalan atau lari',
                'related_challenge_id' => null,
                'related_collection_id' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Collection Conqueror',
                'description' => 'Berhasil menyelesaikan seluruh seri tantangan dalam satu koleksi.',
                'badge_icon' => 'badge-collection-conqueror.png',
                'category' => 'collection',
                'rarity' => 'rare',
                'unlock_requirement' => 'Selesaikan 1 koleksi tantangan hingga 100%',
                'related_challenge_id' => null,
                'related_collection_id' => $firstCollection ? $firstCollection->id : null,
                'status' => 'active',
            ],
            [
                'name' => 'Marathon Master 42K',
                'description' => 'Akumulasi total jarak tempuh mencapai 42.195 kilometer.',
                'badge_icon' => 'badge-marathon-master.png',
                'category' => 'challenge',
                'rarity' => 'epic',
                'unlock_requirement' => 'Akumulasi total jarak 42 km',
                'related_challenge_id' => null,
                'related_collection_id' => null,
                'status' => 'active',
            ],
            [
                'name' => '7-Day Streak Warrior',
                'description' => 'Menjaga kebiasaan aktif berjalan setiap hari selama 7 hari berturut-turut.',
                'badge_icon' => 'badge-streak-warrior.png',
                'category' => 'streak',
                'rarity' => 'rare',
                'unlock_requirement' => 'Capai streak aktivitas harian selama 7 hari',
                'related_challenge_id' => null,
                'related_collection_id' => null,
                'status' => 'active',
            ],
            [
                'name' => 'StepUp Grand Legend',
                'description' => 'Penghargaan tertinggi bagi atlet komunitas yang menyelesaikan event spesial tahunan.',
                'badge_icon' => 'badge-grand-legend.png',
                'category' => 'special_event',
                'rarity' => 'legendary',
                'unlock_requirement' => 'Capai 500.000 langkah dan selesaikan 5 tantangan',
                'related_challenge_id' => null,
                'related_collection_id' => null,
                'status' => 'active',
            ],
        ];

        foreach ($badges as $badge) {
            Badge::firstOrCreate(
                ['name' => $badge['name']],
                $badge,
            );
        }
    }
}
