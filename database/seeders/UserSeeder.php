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
            'role' => 'super_admin',
            'is_primary' => true,
        ]);
    }
}
