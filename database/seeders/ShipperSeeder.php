<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shipper;
use Carbon\Carbon;

class ShipperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shipper::create([
            'user_id' => 4,
            'shipper_name' => 'PT. Maju Jaya Shipping',
            'shipper_type' => 'DIRECT SHIPPER',
            'shipper_city' => 'Jakarta',
            'shipper_address' => 'Jl. Industri No. 123, Jakarta Utara',
            'contact_person' => 'Budi Santoso',
            'phone_number' => '081234567890',
            'email_address' => 'budi@majujaya.com',
            'export' => 'Korea',
            'import' => 'China',
            'domestic' => 'Makassar',
            'commodity' => 'Electronics',
            'input_date' => Carbon::now(),
        ]);

        Shipper::create([
            'user_id' => 5,
            'shipper_name' => 'CV. Berkah Logistik',
            'shipper_type' => 'FORWARDING',
            'shipper_city' => 'Surabaya',
            'shipper_address' => 'Jl. Tanjung Perak No. 45, Surabaya',
            'contact_person' => 'Siti Aminah',
            'phone_number' => '082345678901',
            'email_address' => 'siti@berkah.com',
            'export' => 'Malaysia',
            'import' => 'Thailand',
            'domestic' => 'Medan',
            'commodity' => 'Food & Beverage',
            'input_date' => Carbon::now(),
        ]);
    }
}
