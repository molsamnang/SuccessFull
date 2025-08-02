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

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => $data['password'],
                    'role'     => $data['role'], // optional column in users table
                ]
            );

            $user->assignRole($data['role']);
        }
    }
}
