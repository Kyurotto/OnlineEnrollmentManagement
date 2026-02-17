<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::forceCreate([
                'name' => 'admin',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'status' => 'Active',
            ]);
        }

        // 2. Registrar
        if (!User::where('email', 'registrar@example.com')->exists()) {
            User::forceCreate([
                'name' => 'registrar',
                'username' => 'registrar',
                'email' => 'registrar@example.com',
                'password' => Hash::make('password'),
                'role' => 'registrar',
                'first_name' => 'Registrar',
                'last_name' => 'User',
                'status' => 'Active',
            ]);
        }

        // 3. Cashier
        if (!User::where('email', 'cashier@example.com')->exists()) {
            User::forceCreate([
                'name' => 'cashier',
                'username' => 'cashier',
                'email' => 'cashier@example.com',
                'password' => Hash::make('password'),
                'role' => 'cashier',
                'first_name' => 'Cashier',
                'last_name' => 'User',
                'status' => 'Active',
            ]);
        }

        // 4. Test Student
        if (!User::where('email', 'student@example.com')->exists()) {
            User::forceCreate([
                'name' => 'student',
                'username' => 'student',
                'email' => 'student@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'middle_name' => 'Test',
                'status' => 'Active',
                'email_verified_at' => now(),
            ]);
        }
    }
}
