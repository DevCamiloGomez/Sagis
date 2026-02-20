<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Exception;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $person = \App\Models\Person::firstOrCreate(
            ['email' => 'ingsistemas@ufps.edu.co'],
            [
                'name' => 'Administrador',
                'lastname' => 'Sistema',
                'document_type_id' => 1,
                'document' => '123456789',
                'phone' => '1234567890',
                'address' => 'Universidad Francisco de Paula Santander',
                'birthdate_place_id' => 1,
                'birthdate' => '1990-01-01',
                'has_data_person' => true
            ]
            );

            $admin = \App\Models\Admin::firstOrCreate(
            ['email' => 'ingsistemas@ufps.edu.co'],
            [
                'person_id' => $person->id,
                'password' => 'admin123'
            ]
            );

            // Asignar el rol de admin usando el sistema de Spatie
            $admin->assignRole('admin');
        }
        catch (Exception $th) {
            print($th->getMessage());
        }
    }
}