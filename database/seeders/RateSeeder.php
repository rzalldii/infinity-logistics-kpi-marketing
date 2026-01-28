<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rate;
use Carbon\Carbon;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rate::create([
            'user_id' => 4,
            'pol' => 'Jakarta',
            'pod' => 'Singapore',
            'container_type' => 'RF',
            'container_20' => '450',
            'container_40' => '850',
            'liner' => 'Maersk',
            'free_time' => '7 Days DEM',
            'valid_date' => Carbon::now(),
            'notes' => 'Test',
        ]);

        Rate::create([
            'user_id' => 5,
            'pol' => 'Surabaya',
            'pod' => 'Port Klang',
            'container_type' => 'GP',
            'container_20' => '150',
            'container_40' => '250',
            'liner' => 'Infinity',
            'free_time' => '5 Days DET',
            'valid_date' => Carbon::now(),
            'notes' => 'Test1',
        ]);
    }
}
