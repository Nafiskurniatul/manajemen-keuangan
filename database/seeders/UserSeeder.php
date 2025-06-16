<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Staf Keuangan',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('staff'),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Manajer Keuangan',
            'email' => 'manajer@gmail.com',
            'password' => Hash::make('manajer'),
            'role' => 'manajer',
        ]);
    }
}
