<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ParkingPlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\ParkingPlace::factory(100)->create();
    }
}
