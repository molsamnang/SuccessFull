<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure roles exist
        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'writer']);
        Role::firstOrCreate(['name' => 'customer']);

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('11223344'),
                'role' => 'super_admin',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('11223344'),
                'role' => 'admin',
            ],
            [
                'name' => 'Writer',
                'email' => 'writer@example.com',
                'password' => Hash::make('11223344'),
                'role' => 'writer',
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@example.com',
                'password' => Hash::make('11223344'),
                'role' => 'customer',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'role' => $userData['role'], // Keep if you still want to keep the role column
                ]
            );

            // Assign Spatie role
            $user->assignRole($userData['role']);
        }
    }
}
