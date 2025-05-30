<?php

namespace Database\Seeders;

use App\Models\ManagementFund;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FundManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ManagementFund::create([
            "fund" => 0,
            "name" => "Operasional",
        ]);
        ManagementFund::create([
            "fund" => 0,
            "name" => "Pemasukan",
        ]);
        ManagementFund::create([
            "fund" => 0,
            "name" => "Keuntungan",
        ]);
        ManagementFund::create([
            "fund" => 0,
            "name" => "Zakat",
        ]);
    }
}
