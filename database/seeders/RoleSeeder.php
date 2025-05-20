<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rol para graduado (usuarios normales)
        Role::firstOrCreate(
            ['name' => 'graduado'],
            [
                'fullname' => 'Graduado',
                'guard_name' => 'web'
            ]
        );

        // Rol para administrador
        Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'fullname' => 'Administrador',
                'guard_name' => 'admin'
            ]
        );
    }
}
