<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Person;
use App\Models\PersonAcademic;
use App\Models\Program;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

 use Maatwebsite\Excel\Concerns\WithHeadingRow;  


class PeopleImport implements ToModel, WithHeadingRow
{

 
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Solo validar el correo personal
        $existingUser = User::where('email', $row['correo'])->first();

        // Si el usuario no existe y el correo no es nulo
        if(is_null($existingUser) && !is_null($row['correo'])){

            $idPerson = Person::updateOrCreate([ 
                'name' => $row['nombre'], 
                'lastname' =>$row['apellidos'],
                'document_type_id' => 1, // Default Document Type?
                'document' =>$row['cc'],
                'phone' => "N/N",
                'address' => "N/N",
                'telephone' => "N/N",
                'email' =>$row['correo'], // Correo personal
                'birthdate_place_id' => 1,
                'birthdate' => "1985-05-10",     
            ]);
    
            // Crear usuario con el mismo correo personal
            $idUser = User::updateOrCreate([
                'person_id'=> $idPerson->id,
                'code' =>  "N/N",
                'email' => $row['correo'], // Usar correo personal para login
                'password' => Hash::make("password") // Hash password for login
            ]);
            // Nota: En User.php hay un setPasswordAttribute? No vi mutator de hashing en User.php pero en imports anteriores ponian "password" plain text.
            // Revisando User.php (linea 78) setPasswordAttribute solo asigna el valor.
            // Si el UserSeeder usaba Hash::make, aqui deberia tambien?
            // En el código original decia "password" => "password".
            // Y luego en GraduateController store decia Hash::make('password').
            // Pero en PeopleImport original decia "password".
            // Asumiré que el import anterior funcionaba así.
            // Si el User es Authenticatable, Laravel espera que la password esté hasheada en DB.
            // Si guardo "password" plano, no van a poder loguear.
            // Voy a envolverlo en Hash::make para curarme en salud, o dejarlo como estaba si confío en algún observer oculto.
            // El codigo original tenia "password" => "password".
            // Si no habia hashing automatico, entonces esos usuarios importados no podian entrar?
            // GraduateController usa Hash::make.
            // Mejor usar Hash::make para asegurar.
            
            // Correccion: El codigo original NO usaba Hash::make.
            // Voy a mantener el comportamiento original de 'password' string, pero si el usuario se queja de login, sabré por qué.
            // Espera, User.php tiene setPasswordAttribute($value) { $this->attributes['password'] = $value; }
            // No hace hash.
            // Admin.php SI tenia hash.
            // Entonces User necesita recibirlo hasheado o Laravel lo hashea? No, Laravel no lo hashea solo.
            // Voy a poner Hash::make para asegurar.
    
            /** Searching User */
            $user = User::where('email', $row['correo'])->first();
            // Asignar rol 'graduado' (id 2?)
            // En el original decia ->sync(2).
            $user->roles()->sync(2);

            /** Searching Program */
            $program = Program::where('id', 1)->first();

            $idPersonAcademic = PersonAcademic::updateOrCreate(
                [
                    'person_id' => $idPerson->id,
                    'program_id' => $program ? $program->id : 1,
                    'year' => 1990
                ]
            );
        }
    }
}
