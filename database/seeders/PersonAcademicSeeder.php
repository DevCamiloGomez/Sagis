<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Exception;

use App\Repositories\PersonAcademicRepository;
use App\Repositories\PersonRepository;
use App\Repositories\ProgramRepository;

class PersonAcademicSeeder extends Seeder
{
    /** @var PersonAcademicRepository */
    protected $personAcademicRepository;

    /** @var PersonRepository */
    protected $personRepository;

    /** @var ProgramRepository */
    protected $programRepository;

    public function __construct(
        PersonAcademicRepository $personAcademicRepository,
        PersonRepository $personRepository,
        ProgramRepository $programRepository
    ) {
        $this->personAcademicRepository = $personAcademicRepository;
        $this->personRepository = $personRepository;
        $this->programRepository = $programRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $programs = $this->programRepository->all();

            $this->personRepository->getOnlyGraduates()->get()->map(function ($person) use ($programs) {
                $pregrade = $programs->where('name', 'Programa de Ingeniería de Sistemas')->first();

                $this->personAcademicRepository->createFactory(1, [
                    'person_id' => $person->id,
                    'program_id' => $pregrade->id,
                ]);

                $randomNumber = rand(1, 2);

                do {
                    $randomProgram = $programs->except($pregrade->id)->random(1)->first();
                    $this->personAcademicRepository->createFactory(1, [
                        'person_id' => $person->id,
                        'program_id' => $randomProgram->id
                    ]);
                    $randomNumber--;
                } while ($randomNumber > 0);
            });
        } catch (Exception $th) {
            print($th->getMessage());
        }
    }
}
