<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@anmc.assam.gov.in'],
            [
                'name'      => 'Super Administrator',
                'password'  => Hash::make('password'),
                'role'      => 'super_admin',
                'is_active' => true,
            ]
        );

        $admin->assignRole('super_admin');

        // Editor
        $editor = User::firstOrCreate(
            ['email' => 'editor@anmc.assam.gov.in'],
            [
                'name'      => 'Editor',
                'password'  => Hash::make('password'),
                'role'      => 'editor',
                'is_active' => true,
            ]
        );
        $editor->assignRole('editor');
    }
}
