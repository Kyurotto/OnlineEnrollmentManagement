<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee; // Import the Employee model

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

        Employee::create([
            'last_name' => 'Brown',
            'first_name' => 'Alice',
            'email' => 'alice.brown@example.com',
            'phone' => '09001112222',
            'address' => '789 Pine Rd, Anytown',
            'role' => 'cashier',
        ]);
    }
}
