<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Optional: tetap panggil kalau mau produk default
        $this->call([
            SettingsSeeder::class,
            ProductSeeder::class,
        ]);

        // HANYA ADMIN
        User::updateOrCreate(
            ['email' => 'admin@upcireng.test'],
            [
                'name' => 'Pemilik UP Cireng',
                'phone' => '6285189014426',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}