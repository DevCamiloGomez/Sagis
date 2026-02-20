<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Exception;

use App\Repositories\CityRepository;
use App\Repositories\DocumentTypeRepository;
use App\Repositories\PersonRepository;

class PersonSeeder extends Seeder
{
    /** @var PersonRepository */
    protected $personRepository;

    /** @var DocumentTypeRepository */
    protected $documentTypeRepository;

    /** @var CityRepository */
    protected $cityRepository;

    public function __construct(
        PersonRepository $personRepository,
        DocumentTypeRepository $documentTypeRepository,
        CityRepository $cityRepository
        )
    {
        $this->personRepository = $personRepository;
        $this->documentTypeRepository = $documentTypeRepository;
        $this->cityRepository = $cityRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $randomNumber = env('RANDOM_PEOPLE', 75);

            $documentTypes = $this->documentTypeRepository->all();
            $cities = $this->cityRepository->all();

            $cucutaCity = $cities->where('slug', 'cuc')->first() ?? $cities->first();
            $ccDocument = $documentTypes->where('slug', 'c.c')->first() ?? $documentTypes->first();

            if (!$cucutaCity || !$ccDocument) {
                // Si no hay ciudades o tipos de documento, no podemos crear personas
                // Pero esto no debería pasar si los seeders anteriores corrieron
                return;
            }

            // Crear o actualizar el primer administrador
            \App\Models\Person::firstOrCreate(
            ['email' => 'jarlinandres5000@gmail.com'],
            [
                'name' => 'Jarlin Andrés',
                'lastname' => 'Fonseca',
                'document' => '1006287478',
                'document_type_id' => $ccDocument->id,
                'birthdate_place_id' => $cucutaCity->id,
                'phone' => '1234567890',
                'address' => 'Universidad Francisco de Paula Santander',
                'birthdate' => '1990-01-01',
                'has_data_person' => true
            ]
            );

            // Crear o actualizar el segundo administrador
            \App\Models\Person::firstOrCreate(
            ['email' => 'judithdelpilarrt@ufps.edu.co'],
            [
                'name' => 'Judith del pilar',
                'lastname' => 'Rodríguez Tenjo',
                'document' => '1234567890',
                'document_type_id' => $ccDocument->id,
                'birthdate_place_id' => $cucutaCity->id,
                'phone' => '1234567890',
                'address' => 'Universidad Francisco de Paula Santander',
                'birthdate' => '1990-01-01',
                'has_data_person' => true
            ]
            );

            // Crear registros aleatorios solo si no existen
            do {
                try {
                    $this->personRepository->createFactory(1, [
                        'document_type_id' => $documentTypes->random(1)->first()->id,
                        'birthdate_place_id' => $cities->random(1)->first()->id
                    ]);
                }
                catch (\Exception $e) {
                    // Si hay un error de duplicado, continuamos con el siguiente
                    if (!str_contains($e->getMessage(), 'Duplicate entry')) {
                        throw $e;
                    }
                }
                $randomNumber--;
            } while ($randomNumber > 0);
        }
        catch (Exception $th) {
            print($th->getMessage());
        }
    }
}