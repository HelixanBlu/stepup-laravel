<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin StepUp',
            'email' => 'admin@stepup.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'status' => 'active',
            'poin' => 0,
        ]);

        // Sample users
        $users = [
            ['name' => 'Andi Pratama', 'email' => 'andi@stepup.com', 'poin' => 1250],
            ['name' => 'Budi Santoso', 'email' => 'budi@stepup.com', 'poin' => 870],
            ['name' => 'Citra Dewi', 'email' => 'citra@stepup.com', 'poin' => 2300],
            ['name' => 'Dina Rahayu', 'email' => 'dina@stepup.com', 'poin' => 500],
            ['name' => 'Eko Wijaya', 'email' => 'eko@stepup.com', 'poin' => 3100],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('123'),
                'role' => 'user',
                'status' => 'active',
                'poin' => $userData['poin'],
            ]);
        }
    }
}
