<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Check if admin exits
        if (!User::where('email', 'admin@deped.gov.ph')->exists()) {
            User::create([
                'name' => 'System Administrator',
                'email' => 'admin@deped.gov.ph',
                'password' => Hash::make('Admin123!'), // Default strong password
                'status' => 'active',
                'role' => 'admin',
                'account_type' => 'individual',
                'email_verified_at' => now(),
                'activated_at' => now(),
            ]);
        }
    }
}
