<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            [
                'name_reward' => 'Tumbler StepUp Sport 750ml',
                'description' => 'Tumbler stainless steel tahan dingin 24 jam dengan logo StepUp eksklusif.',
                'poin' => 150,
                'gambar' => 'tumbler-sport.png',
                'stok' => 25,
            ],
            [
                'name_reward' => 'Voucher Diskon Sepatu Rp 50.000',
                'description' => 'Voucher potongan belanja sepatu lari di official store partner.',
                'poin' => 200,
                'gambar' => 'voucher-50k.png',
                'stok' => 50,
            ],
            [
                'name_reward' => 'Jersey Lari StepUp Dry-Fit',
                'description' => 'Jersey olahraga breathable ringan dan nyaman untuk lari jarak jauh.',
                'poin' => 350,
                'gambar' => 'jersey-running.png',
                'stok' => 15,
            ],
            [
                'name_reward' => 'Hand Grip Adjustable 10-60kg',
                'description' => 'Alat latihan kekuatan genggaman tangan dengan resistance yang dapat disesuaikan.',
                'poin' => 100,
                'gambar' => 'hand-grip.png',
                'stok' => 40,
            ],
            [
                'name_reward' => 'Smartwatch Sport Strap Silicone',
                'description' => 'Tali pengganti smartwatch bahan silikon anti-keringat ukuran 20mm/22mm.',
                'poin' => 120,
                'gambar' => 'watch-strap.png',
                'stok' => 30,
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}
