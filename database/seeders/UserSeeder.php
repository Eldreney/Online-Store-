<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ↓↓↓ crete `super admin` user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super_admin@laravel.com',
            'role' => 2,
            'password' => Hash::make('P@ssW0rd123'),
        ]);
        // ↓↓↓ assign `super admin` to user
        $superAdmin->assignRole('super_admin');

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@laravel.com',
            'role' => 2,
            'password' => Hash::make('P@ssW0rd123'),
        ]);
        $admin->assignRole('admin');


        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@laravel.com',
            'role' => 1,
            'password' => Hash::make('P@ssW0rd123'),
        ]);
        $customer->assignRole('customer');

    }

}
