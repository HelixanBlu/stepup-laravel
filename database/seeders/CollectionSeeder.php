<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collections = [
            [
                'name' => 'Beginner 7-Day Walking Starter',
                'description' => 'Mulai kebiasaan hidup aktif dengan target jalan santai harian bertahap selama 7 hari.',
                'cover_image' => 'col-beginner-walk.jpg',
                'difficulty' => 'easy',
                'estimated_duration' => '7 Hari',
                'status' => 'active',
            ],
            [
                'name' => '10K Daily Steps Challenge Series',
                'description' => 'Koleksi tantangan mencapai 10.000 langkah setiap hari untuk meningkatkan kebugaran kardio.',
                'cover_image' => 'col-10k-steps.jpg',
                'difficulty' => 'medium',
                'estimated_duration' => '14 Hari',
                'status' => 'active',
            ],
            [
                'name' => 'Weekend Trail Runner Explorer',
                'description' => 'Jelajahi rute outdoor akhir pekan dengan target jarak dan elevasi menantang.',
                'cover_image' => 'col-trail-runner.jpg',
                'difficulty' => 'hard',
                'estimated_duration' => '4 Akhir Pekan',
                'status' => 'active',
            ],
            [
                'name' => 'Fat Burner Cardio Booster',
                'description' => 'Kombinasi jalan cepat dan jogging intensif untuk membakar kalori optimal.',
                'cover_image' => 'col-fat-burner.jpg',
                'difficulty' => 'medium',
                'estimated_duration' => '10 Hari',
                'status' => 'active',
            ],
            [
                'name' => 'Monthly Consistency Master',
                'description' => 'Tantangan konsistensi penuh 30 hari tanpa jeda untuk membangun rutinitas sehat seumur hidup.',
                'cover_image' => 'col-monthly-master.jpg',
                'difficulty' => 'hard',
                'estimated_duration' => '30 Hari',
                'status' => 'active',
            ],
        ];

        foreach ($collections as $collection) {
            Collection::create($collection);
        }
    }
}
