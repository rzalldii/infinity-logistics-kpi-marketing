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
            'container_type' => 'GP',
            'container_20' => '450',
            'container_40' => '850',
            'liner' => 'Maersk Line',
            'free_time' => '7 days',
            'valid_date' => Carbon::now(),
            'notes' => 'Weekly schedule, fast transit',
        ]);

        Rate::create([
            'user_id' => 5,
            'pol' => 'Surabaya',
            'pod' => 'Port Klang',
            'container_type' => 'HC',
            'container_20' => '150',
            'container_40' => '250',
            'liner' => 'Infinity',
            'free_time' => '5 days',
            'valid_date' => Carbon::now(),
            'notes' => 'Direct service',
        ]);
    }
}
