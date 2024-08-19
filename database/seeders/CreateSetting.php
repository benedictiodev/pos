<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateSetting extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanySetting::create([
            'company_id' => 1, 
            'latitude' => -7.002723748974052,
            'longitude' => 107.62206899053928,
            'distance' => 15,
        ]);
    }
}
