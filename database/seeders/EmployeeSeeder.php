<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('role', 'admin')->first();
        Employee::create([
            'last_name' => 'Doe',
            'first_name' => 'John',
            'email' => 'john.doe@example.com',
            'phone' => '09123456789',
            'address' => '123 Main St, Anytown',
            'role' => 'admin',
            'user_id' => $adminUser ? $adminUser->id : null,
        ]);

        $registrarUser = User::where('role', 'registrar')->first();
        Employee::create([
            'last_name' => 'Smith',
            'first_name' => 'Jane',
            'email' => 'jane.smith@example.com',
            'phone' => '09987654321',
            'address' => '456 Oak Ave, Anytown',
            'role' => 'registrar',
            'user_id' => $registrarUser ? $registrarUser->id : null,
        ]);

        $cashierUser = User::where('role', 'cashier')->first();
        Employee::create([
            'last_name' => 'Brown',
            'first_name' => 'Alice',
            'email' => 'alice.brown@example.com',
            'phone' => '09001112222',
            'address' => '789 Pine Rd, Anytown',
            'role' => 'cashier',
            'user_id' => $cashierUser ? $cashierUser->id : null,
        ]);
    }
}
