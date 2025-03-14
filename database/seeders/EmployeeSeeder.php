<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Team;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::factory()->count(5)->create();
    }
}
