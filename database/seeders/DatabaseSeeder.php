<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        Role::create(['name' => 'Customer']);
        Role::create(['name' => 'Writer']);
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Super Admin']);

        
    }
}
