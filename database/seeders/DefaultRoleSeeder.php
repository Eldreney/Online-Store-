<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DefaultRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // all basic permissions
        $permissions = [
            'user_management_access',
            'permission_management_access',
            'role_management_access',

            'permission_create',
            'permission_edit',
            'permission_show',
            'permission_delete',
            'permission_access',

            'role_create',
            'role_edit',
            'role_show',
            'role_delete',
            'role_access',

            'user_create',
            'user_edit',
            'user_show',
            'user_delete',
            'user_access',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // gets all permissions via Gate::before rule; see AuthServiceProvider
        // all permissions of `super admin` users
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $superAdminRole->syncPermissions($permissions);

        // all permissions of `admin` users
        $adminRole = Role::create(['name' => 'admin']);
        $adminPermissions = [
            'user_management_access',

            'user_create',
            'user_edit',
            'user_show',
            'user_delete',
            'user_access',
        ];
        $adminRole->syncPermissions($adminPermissions);

        // all permissions of `customer users
        $customerUserRole = Role::create(['name' => 'customer']);
        $standardUserPermissions = [];
        $customerUserRole->syncPermissions($standardUserPermissions);

    }

}
