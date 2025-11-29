<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@qsights.com',
            'password' => Hash::make('SuperAdmin@123'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $superAdmin->assignRole('super_admin');
    }
}
