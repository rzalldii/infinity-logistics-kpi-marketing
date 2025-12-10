<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Activity::create([
            'user_id' => 4,
            'concept_type' => 'NEW SHIPPER',
            'shipper_id' => 1,
            'activity_type' => 'VISIT',
            'visit_date' => Carbon::now()->subDays(3),
            'status' => 'PENDING',
            'status_detail' => 'Waiting for client decision on quotation',
            'prospect' => 'Interested in export services to Korea',
        ]);

        Activity::create([
            'user_id' => 5,
            'concept_type' => 'FOLLOW UP',
            'shipper_id' => 2,
            'activity_type' => 'CALL',
            'visit_date' => null,
            'status' => 'CLOSING',
            'status_detail' => '10 containers GP 20ft',
            'prospect' => 'Discussing container availability for next month',
        ]);
    }
}