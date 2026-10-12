<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@satak.local',
                'password' => 'Admin123!',
                'role' => UserRole::SuperAdmin,
                'is_active' => true,
            ]
        );
    }
}
