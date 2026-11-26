<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    public function run(): void
    {
        $rewards = [
            [
                'name_reward' => 'Voucher Kopi Gratis',
                'description' => 'Dapatkan satu cup kopi gratis di kantin kampus.',
                'poin' => 500,
                'stok' => 50,
            ],
            [
                'name_reward' => 'Diskon 20% Kantin',
                'description' => 'Diskon 20% untuk pembelian di kantin kampus berlaku satu hari.',
                'poin' => 300,
                'stok' => 100,
            ],
            [
                'name_reward' => 'Merchandise StepUp T-Shirt',
                'description' => 'Kaos eksklusif StepUp dengan desain limited edition.',
                'poin' => 2000,
                'stok' => 20,
            ],
            [
                'name_reward' => 'Voucher Gym 1 Bulan',
                'description' => 'Akses gratis ke gym kampus selama satu bulan penuh.',
                'poin' => 5000,
                'stok' => 10,
            ],
            [
                'name_reward' => 'Tumbler StepUp',
                'description' => 'Tumbler stainless steel StepUp edisi terbatas.',
                'poin' => 1500,
                'stok' => 30,
            ],
            [
                'name_reward' => 'Voucher Makan Siang',
                'description' => 'Voucher makan siang gratis di kafetaria kampus.',
                'poin' => 800,
                'stok' => 75,
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}
