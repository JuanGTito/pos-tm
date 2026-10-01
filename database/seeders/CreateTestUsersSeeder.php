<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CreateTestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'admin',
                'password' => bcrypt('admin'),
                'email_verified_at' => now(),
            ]
        );
        
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
        $this->command->info('Admin user created: admin/admin');

        // Create regular user
        $user = User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'user',
                'password' => bcrypt('user'),
                'email_verified_at' => now(),
            ]
        );
        
        if (!$user->hasRole('user')) {
            $user->assignRole('user');
        }
        $this->command->info('Regular user created: user/user');
    }
}
