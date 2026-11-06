<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Primary Admin Account (Matches login page 1-click demo button)
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Admin@123456'),
                'role' => 'admin',
                'status' => 'active',
                'is_verified' => true,
                'is_deleted' => false,
                'is_profile_completed' => true,
            ]
        );

        // 2. Alternative Easy Admin Account
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'is_verified' => true,
                'is_deleted' => false,
                'is_profile_completed' => true,
            ]
        );

        // 3. Customer Account (Matches login page 1-click demo button)
        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'John Customer',
                'password' => Hash::make('Customer@123456'),
                'role' => 'client',
                'status' => 'active',
                'is_verified' => true,
                'is_deleted' => false,
                'is_profile_completed' => true,
            ]
        );

        // 4. Alternative Easy User Account
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'role' => 'client',
                'status' => 'active',
                'is_verified' => true,
                'is_deleted' => false,
                'is_profile_completed' => true,
            ]
        );

        // 5. Seed E-Commerce Catalog (Categories, Subcategories, Products)
        $this->call(EcommerceCatalogSeeder::class);
    }
}
