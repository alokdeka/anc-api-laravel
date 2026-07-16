<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Circulars
            'create circulars', 'edit circulars', 'delete circulars', 'publish circulars',
            // Registrations
            'view registrations', 'create registrations', 'edit registrations',
            'approve registrations', 'revoke registrations',
            // Institutes
            'view institutes', 'create institutes', 'edit institutes', 'delete institutes',
            // Examinations
            'view examinations', 'create examinations', 'edit examinations', 'upload results',
            // Users
            'manage users',
            // Settings
            'manage settings',
            // Audit Log
            'view audit log',
            // Forms
            'manage forms',
            // Media
            'manage media',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            'super_admin' => $permissions, // All permissions
            'registrar'   => [
                'view registrations', 'create registrations', 'edit registrations',
                'approve registrations', 'revoke registrations',
                'view institutes', 'create institutes', 'edit institutes',
                'create circulars', 'edit circulars', 'publish circulars',
                'view audit log', 'manage forms',
            ],
            'editor' => [
                'create circulars', 'edit circulars', 'publish circulars',
                'manage forms', 'manage media',
            ],
            'exam_cell' => [
                'view examinations', 'create examinations', 'edit examinations', 'upload results',
                'manage media',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
