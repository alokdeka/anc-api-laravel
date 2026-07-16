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

        // Registrar
        $registrar = User::firstOrCreate(
            ['email' => 'registrar@anmc.assam.gov.in'],
            [
                'name'      => 'Council Registrar',
                'password'  => Hash::make('password'),
                'role'      => 'registrar',
                'is_active' => true,
            ]
        );
        $registrar->assignRole('registrar');
    }
}
