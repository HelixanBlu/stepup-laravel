<?php

namespace Database\Seeders;

use App\Models\Challenge;
use App\Models\Collection;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        $collections = [
            [
                'name' => 'Beginner Fitness Pack',
                'description' => 'Paket tantangan untuk pemula yang baru memulai gaya hidup aktif.',
                'difficulty' => 'easy',
                'estimated_duration' => '2 Minggu',
                'status' => 'active',
                'challenge_indices' => [0, 1],
            ],
            [
                'name' => 'Advanced Warrior',
                'description' => 'Kumpulan tantangan untuk yang sudah berpengalaman dan ingin mendorong batas.',
                'difficulty' => 'hard',
                'estimated_duration' => '1 Bulan',
                'status' => 'active',
                'challenge_indices' => [2, 3],
            ],
            [
                'name' => 'Campus Active Week',
                'description' => 'Tantangan khusus mahasiswa untuk tetap aktif selama perkuliahan.',
                'difficulty' => 'medium',
                'estimated_duration' => '1 Minggu',
                'status' => 'active',
                'challenge_indices' => [0, 4],
            ],
        ];

        $challenges = Challenge::pluck('id')->toArray();

        foreach ($collections as $data) {
            $challengeIndices = $data['challenge_indices'];
            unset($data['challenge_indices']);

            $collection = Collection::create($data);

            $selectedChallengeIds = array_filter(array_map(
                fn ($i) => $challenges[$i] ?? null,
                $challengeIndices
            ));

            if (! empty($selectedChallengeIds)) {
                $collection->challenges()->sync($selectedChallengeIds);
            }
        }
    }
}
