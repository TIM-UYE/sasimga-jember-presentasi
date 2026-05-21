<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Manager - full backend access
        User::updateOrCreate(
            ['email' => 'manager@sasimga.com'],
            [
                'nama' => 'Manager',
                'password' => Hash::make('manager123'),
                'role' => 'manager',
            ]
        );

        // Admin - only transactions & reservations
        User::updateOrCreate(
            ['email' => 'admin@sasimga.com'],
            [
                'nama' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Owner - read-only analytics dashboard
        User::updateOrCreate(
            ['email' => 'owner@sasimga.com'],
            [
                'nama' => 'Owner',
                'password' => Hash::make('owner123'),
                'role' => 'owner',
            ]
        );
    }
}
