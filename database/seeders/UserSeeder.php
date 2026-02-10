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
    }
}
