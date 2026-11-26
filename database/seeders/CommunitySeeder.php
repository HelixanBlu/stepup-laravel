<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Community;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();

        if ($users->isEmpty()) {
            return;
        }

        $posts = [
            [
                'deskripsi' => 'Hari ini berhasil capai 12.000 langkah! Semangat terus untuk yang lain 💪',
                'langkah' => 12000,
                'jarak' => 8.5,
                'kalori' => 320,
            ],
            [
                'deskripsi' => 'Pagi-pagi jogging 5K sebelum kuliah, badan jadi segar seharian!',
                'langkah' => 6500,
                'jarak' => 5.0,
                'kalori' => 280,
            ],
            [
                'deskripsi' => 'Sudah selesaikan Weekly 5K Run Challenge dalam 3 hari! 🏃‍♂️',
                'langkah' => 8000,
                'jarak' => 5.2,
                'kalori' => 350,
            ],
            [
                'deskripsi' => 'Siapa mau join team challenge bareng? Yuk kumpul di lapangan jam 6 pagi!',
                'langkah' => 5000,
                'jarak' => 3.0,
                'kalori' => 150,
            ],
            [
                'deskripsi' => 'Reward voucher kopi sudah bisa di-redeem! Terima kasih StepUp 🎉',
                'langkah' => 10500,
                'jarak' => 7.1,
                'kalori' => 410,
            ],
        ];

        $commentTexts = [
            'Keren banget! Keep it up! 👏',
            'Wah mantap, aku juga mau coba nih',
            'Inspiratif banget, semangat!',
            'Luar biasa, sudah berapa hari streak-nya?',
            'GG, aku masih 7K nih belum nyampe 10K 😅',
        ];

        foreach ($posts as $index => $postData) {
            $user = $users[$index % $users->count()];

            $post = Community::create(array_merge($postData, ['user_id' => $user->id]));

            // Add 2-3 comments per post
            $commentCount = rand(2, 3);
            for ($i = 0; $i < $commentCount; $i++) {
                $commenter = $users[($index + $i + 1) % $users->count()];
                Comment::create([
                    'community_id' => $post->id,
                    'user_id' => $commenter->id,
                    'nama_user' => $commenter->name,
                    'komentar' => $commentTexts[($index + $i) % count($commentTexts)],
                ]);
            }
        }
    }
}
