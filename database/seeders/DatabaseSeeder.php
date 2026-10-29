<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Lumikha ng Admin Account
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Hanapin kung may umiiral na
            [
                'name' => 'Portal Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin12345'), // Admin Password
                'role' => 'admin', // Naka-set bilang Admin
            ]
        );
    }
}