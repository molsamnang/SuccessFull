<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('11223344'),
                'role' => 'super_admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('11223344'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'writer@example.com'],
            [
                'name' => 'Writer',
                'password' => Hash::make('11223344'),
                'role' => 'writer',
            ]
        );

        User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer',
                'password' => Hash::make('11223344'),
                'role' => 'customer',
            ]
        );
    }
}
