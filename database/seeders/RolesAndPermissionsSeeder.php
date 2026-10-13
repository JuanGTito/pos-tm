<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create permissions
        $permissions = [
            'view sales',
            'create sales',
            'edit sales',
            'delete sales',
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',
            'view inventory entries',
            'create inventory entries',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->syncPermissions(Permission::all());
        $userRole->syncPermissions([
            'view sales',
            'create sales',
            'view products',
        ]);

        // Assign admin role to first user
        $user = User::first();
        if ($user && ! $user->hasRole('admin')) {
            $user->assignRole('admin');
        }

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
