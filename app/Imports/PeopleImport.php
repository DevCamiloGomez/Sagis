<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Person;
use App\Models\PersonAcademic;
use App\Models\Program;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Throwable;

class PeopleImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    public int $importados = 0;
    public int $omitidos   = 0;
    public array $errores  = [];

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Saltar filas vacías
        if (empty($row['correo'])) {
            $this->omitidos++;
            return null;
        }

        // Saltar si el correo ya existe
        $existingUser = User::where('email', $row['correo'])->first();
        if (!is_null($existingUser)) {
            $this->omitidos++;
            Log::info('PeopleImport: correo ya existe, omitiendo.', ['correo' => $row['correo']]);
            return null;
        }

        try {
            $idPerson = Person::updateOrCreate([
                'email' => $row['correo'],
            ], [
                'name'             => $row['nombre']   ?? 'Sin nombre',
                'lastname'         => $row['apellidos'] ?? 'Sin apellidos',
                'document_type_id' => 1,
                'document'         => $row['cc']       ?? 'N/N',
                'phone'            => 'N/N',
                'address'          => 'N/N',
                'telephone'        => 'N/N',
                'birthdate_place_id' => 1,
                'birthdate'        => '1985-05-10',
            ]);

            $idUser = User::updateOrCreate(
                ['email' => $row['correo']],
                [
                    'person_id' => $idPerson->id,
                    'code'      => 'N/N',
                    'password'  => Hash::make('password'),
                ]
            );

            $user = User::where('email', $row['correo'])->first();
            $user->roles()->sync(2);

            $program = Program::find(1);

            PersonAcademic::updateOrCreate([
                'person_id'  => $idPerson->id,
                'program_id' => $program ? $program->id : 1,
                'year'       => 1990,
            ]);

            $this->importados++;
            return null; // ya usamos updateOrCreate directamente

        } catch (\Exception $e) {
            $this->errores[] = "Fila con correo {$row['correo']}: " . $e->getMessage();
            Log::error('PeopleImport error en fila', [
                'correo' => $row['correo'] ?? 'N/A',
                'error'  => $e->getMessage(),
            ]);
            return null;
        }
    }
}
