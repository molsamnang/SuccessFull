<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles (lowercase)
        $roles = ['super_admin', 'admin', 'writer', 'customer'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Call other seeders
        $this->call(UserSeeder::class);
    }
}
