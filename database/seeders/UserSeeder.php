<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Bagas',
            'email' => 'marketing1@infinity-sby.com',
            'password' => Hash::make('admin'),
            'role' => 'SUPER ADMIN',
            'is_primary' => true,
        ]);

        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@infinity-sby.com',
            'password' => Hash::make('admin'),
            'role' => 'SUPER ADMIN',
            'is_primary' => false,
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@infinity-sby.com',
            'password' => Hash::make('admin'),
            'role' => 'ADMIN',
            'is_primary' => false,
        ]);

        User::create([
            'name' => 'Guest Marketing',
            'email' => 'guestmarketing@infinity-sby.com',
            'password' => Hash::make('guest'),
            'role' => 'MARKETING',
            'is_primary' => false,
        ]);

        User::create([
            'name' => 'Guest Marketing 1',
            'email' => 'guestmarketing1@infinity-sby.com',
            'password' => Hash::make('guest'),
            'role' => 'MARKETING',
            'is_primary' => false,
        ]);

        User::create([
            'name' => 'Guest',
            'email' => 'guest@infinity-sby.com',
            'password' => Hash::make('guest'),
            'role' => 'GUEST',
            'is_primary' => false,
        ]);
    }
}
