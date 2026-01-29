<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'admin',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'first_name' => 'Admin',
            'last_name' => 'User',
        ]);

        User::firstOrCreate([
            'email' => 'registrar@example.com',
        ], [
            'name' => 'registrar',
            'username' => 'registrar',
            'password' => Hash::make('password'),
            'role' => 'registrar',
            'first_name' => 'Registrar',
            'last_name' => 'User',
        ]);

        User::firstOrCreate([
            'email' => 'cashier@example.com',
        ], [
            'name' => 'cashier',
            'username' => 'cashier',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'first_name' => 'Cashier',
            'last_name' => 'User',
        ]);
    }
}
