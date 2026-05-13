<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'full_name' => 'Test User',
                'password' => Hash::make('password'),
                'password_hash' => Hash::make('password'),
                'gender' => 'Hidden',
            ]
        );

        User::updateOrCreate(
            ['email' => 'sombath@gmail.com'],
            [
                'full_name' => 'Sombath',
                'password' => Hash::make('password'),
                'password_hash' => Hash::make('password'),
                'gender' => 'Hidden',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'full_name' => 'Admin',
                'password' => Hash::make('Admin123'),
                'password_hash' => Hash::make('Admin123'),
                'gender' => 'Hidden',
                'is_admin' => true,
            ]
        );

        $this->call([
            ProductSeeder::class,
        ]);
    }
}
