<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Crew CC',
            'email' => 'crewcc@example.com',
            'company_id' => 1,
            'username' => 'crewcc',
        ]);
    }
}
