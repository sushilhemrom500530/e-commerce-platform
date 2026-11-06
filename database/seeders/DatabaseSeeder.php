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
        // 1. Seed default Admin and Customer accounts
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Store Admin',
                'password' => Hash::make('Admin@123456'),
                'role' => 'admin',
                'status' => 'active',
                'is_verified' => true,
                'is_deleted' => false,
                'is_profile_completed' => true,
            ]
        );

        User::firstOrCreate(
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

        $adminEmail = env('ADMIN_EMAIL_ONE', 'admin@Practice Backend api.com');
        $adminPassword = env('ADMIN_PASSWORD_ONE', 'Admin@123456');

        User::firstOrCreate(
            ['email' => strtolower(trim($adminEmail))],
            [
                'name' => 'System Administrator',
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
                'status' => 'active',
                'is_verified' => true,
                'is_deleted' => false,
                'is_profile_completed' => true,
            ]
        );

        // 2. Secondary admin if provided
        $adminEmailTwo = env('ADMIN_EMAIL_TWO');
        $adminPasswordTwo = env('ADMIN_PASSWORD_TWO');
        if ($adminEmailTwo && $adminPasswordTwo) {
            User::firstOrCreate(
                ['email' => strtolower(trim($adminEmailTwo))],
                [
                    'name' => 'Secondary Administrator',
                    'password' => Hash::make($adminPasswordTwo),
                    'role' => 'admin',
                    'status' => 'active',
                    'is_verified' => true,
                    'is_deleted' => false,
                    'is_profile_completed' => true,
                ]
            );
        }

        // 3. Seed E-Commerce Catalog (Categories, Subcategories, Products)
        $this->call(EcommerceCatalogSeeder::class);
    }
}
