<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder; // Import the Employee model

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'last_name' => 'Doe',
            'first_name' => 'John',
            'email' => 'john.doe@example.com',
            'phone' => '09123456789',
            'address' => '123 Main St, Anytown',
            'role' => 'admin',
        ]);

        Employee::create([
            'last_name' => 'Smith',
            'first_name' => 'Jane',
            'email' => 'jane.smith@example.com',
            'phone' => '09987654321',
            'address' => '456 Oak Ave, Anytown',
            'role' => 'registrar',
        ]);
    }
}
