<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin Employee
        $adminUser = User::where('role', 'admin')->first();
        if ($adminUser && !Employee::where('user_id', $adminUser->id)->exists()) {
            Employee::create([
                'user_id' => $adminUser->id,
                'first_name' => $adminUser->first_name ?? 'Admin',
                'last_name' => $adminUser->last_name ?? 'User',
                'email' => $adminUser->email,
                'role' => 'admin',
                'phone' => '09123456789', // Edit Admin phone number here
                'address' => 'Admin Office', // Edit Admin address here
            ]);
        }

        // 2. Registrar Employee
        $registrarUser = User::where('role', 'registrar')->first();
        if ($registrarUser && !Employee::where('user_id', $registrarUser->id)->exists()) {
            Employee::create([
                'user_id' => $registrarUser->id,
                'first_name' => $registrarUser->first_name ?? 'Registrar',
                'last_name' => $registrarUser->last_name ?? 'User',
                'email' => $registrarUser->email,
                'role' => 'registrar',
                'phone' => '09123456789', // Edit Registrar phone number here
                'address' => 'Registrar Office', // Edit Registrar address here
            ]);
        }

        // 3. Cashier Employee
        $cashierUser = User::where('role', 'cashier')->first();
        if ($cashierUser && !Employee::where('user_id', $cashierUser->id)->exists()) {
            Employee::create([
                'user_id' => $cashierUser->id,
                'first_name' => $cashierUser->first_name ?? 'Cashier',
                'last_name' => $cashierUser->last_name ?? 'User',
                'email' => $cashierUser->email,
                'role' => 'cashier',
                'phone' => '09123456789', // Edit Cashier phone number here
                'address' => 'Cashier Office', // Edit Cashier address here
            ]);
        }
    }
}
