<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage users',
            'manage settings',
            'manage theme',
            'manage security',
            'manage content',
            'manage products',
            'manage digital menu',
            'manage articles',
            'view articles',
            'create articles',
            'update own articles',
            'update all articles',
            'review articles',
            'publish articles',
            'archive articles',
            'delete articles',
            'view logs',
            'view leads',
            'manage leads',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Create Super Admin Role & Assign all permissions
        $superAdminRole = Role::findOrCreate('Super Admin');
        $superAdminRole->givePermissionTo(Permission::all());

        // Create Editor Role
        $editorRole = Role::findOrCreate('Editor');
        $editorRole->givePermissionTo([
            'manage content',
            'manage products',
            'manage digital menu',
            'manage articles',
            'view articles',
            'create articles',
            'update all articles',
            'review articles',
            'view leads',
        ]);

        // Create Staff Role
        $staffRole = Role::findOrCreate('Staff');
        $staffRole->givePermissionTo([
            'manage products',
            'manage digital menu',
            'manage articles',
            'view articles',
            'create articles',
            'update own articles',
            'view leads',
            'manage leads',
        ]);
    }
}
