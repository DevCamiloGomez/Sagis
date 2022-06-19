<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Repositories\AcademicLevelRepository;

class AcademicLevelSeeder extends Seeder
{
    /**
     * @var AcademicLevelRepository
     */
    protected $academicLevelRepository;

    public function __construct(AcademicLevelRepository $academicLevelRepository)
    {
        $this->academicLevelRepository = $academicLevelRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'pregrado'],
            ['name' => 'especialización'],
            ['name' => 'maestría'],
            ['name' => 'doctorado'],
        ];

        foreach ($items as $item) {
            $this->academicLevelRepository->create($item);
        }
    }
}
