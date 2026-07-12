<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Super Admin User
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@aromaticacoffee.com'],
            [
                'name' => 'Super Admin Aromatica',
                'password' => Hash::make('AromaticaAdmin2026!'),
                'is_active' => true,
            ]
        );
        $superAdminRole = Role::findByName('Super Admin');
        $superAdmin->assignRole($superAdminRole);
        $superAdmin->role_id = $superAdminRole->id;
        $superAdmin->save();

        // 2. Editor User
        $editor = User::updateOrCreate(
            ['email' => 'editor@aromaticacoffee.com'],
            [
                'name' => 'Editor Aromatica',
                'password' => Hash::make('AromaticaEditor2026!'),
                'is_active' => true,
            ]
        );
        $editorRole = Role::findByName('Editor');
        $editor->assignRole($editorRole);
        $editor->role_id = $editorRole->id;
        $editor->save();

        // 3. Staff User
        $staff = User::updateOrCreate(
            ['email' => 'staff@aromaticacoffee.com'],
            [
                'name' => 'Staff Aromatica',
                'password' => Hash::make('AromaticaStaff2026!'),
                'is_active' => true,
            ]
        );
        $staffRole = Role::findByName('Staff');
        $staff->assignRole($staffRole);
        $staff->role_id = $staffRole->id;
        $staff->save();
    }
}
