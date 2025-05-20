<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CountryRepository;
use App\Repositories\PersonAcademicRepository;
use App\Repositories\PersonCompanyRepository;
use App\Repositories\PersonRepository;
use App\Repositories\PostRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

use Faker\Generator as Faker;

use function PHPSTORM_META\map;

class ReportController extends Controller
{
    /** @var PersonRepository */
    protected $personRepository;

    /** @var UserRepository */
    protected $userRepository;

    /** @var RoleRepository */
    protected $roleRepository;

    /** @var PostRepository */
    protected $postRepository;

    /** @var CountryRepository */
    protected $countryRepository;

    /** @var PersonCompanyRepository */
    protected $personCompanyRepository;

    /** @var PersonAcademicRepository */
    protected $personAcademicRepository;

    /** @var Faker */
    protected $faker;

    public function __construct(
        PersonRepository $personRepository,
        UserRepository $userRepository,
        RoleRepository $roleRepository,
        PostRepository $postRepository,
        CountryRepository $countryRepository,
        PersonCompanyRepository $personCompanyRepository,
        PersonAcademicRepository $personAcademicRepository,
        Faker $faker,
    ) {
        $this->middleware('auth:admin');

        $this->personRepository = $personRepository;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->postRepository = $postRepository;
        $this->countryRepository = $countryRepository;
        $this->personCompanyRepository = $personCompanyRepository;
        $this->personAcademicRepository = $personAcademicRepository;
        $this->faker = $faker;
    }

    public function graduates(Request $request)
    {
        try {
            $graduateRole = $this->roleRepository->getByAttribute('name', 'graduado');
            
            // Usar paginación y eager loading optimizado
            $query = $graduateRole->users()
                ->with([
                    'person' => function($query) {
                        $query->select('id', 'name', 'lastname', 'document', 'email', 'birthdate', 'phone', 'telephone', 'address', 'birthdate_place_id')
                            ->with(['birthdatePlace:id,name']);
                    },
                    'person.personAcademic' => function($query) {
                        $query->select('id', 'person_id', 'program_id', 'year')
                            ->with(['program:id,name,faculty_id', 'program.faculty:id,name,university_id', 'program.faculty.university:id,name']);
                    },
                    'person.personCompany' => function($query) {
                        $query->where('in_working', true)
                            ->select('id', 'person_id', 'company_id', 'salary')
                            ->with(['company:id,name,address']);
                    }
                ]);

            // Aplicar búsqueda si existe
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $query->whereHas('person', function($q) use ($searchTerm) {
                    $q->where(function($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('lastname', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('document', 'LIKE', "%{$searchTerm}%");
                    });
                });
            }

            // Si se solicita exportación
            if ($request->has('export')) {
                $items = $query->get();
                
                if ($request->export === 'excel') {
                    return $this->exportToExcel($items);
                } elseif ($request->export === 'pdf') {
                    return $this->exportToPdf($items);
                }
            }

            // Obtener el número de registros por página del request, por defecto 10
            $perPage = $request->input('per_page', 10);
            
            // Asegurarse de que el valor sea uno de los permitidos
            $allowedPerPage = [10, 25, 50, 100];
            if (!in_array($perPage, $allowedPerPage)) {
                $perPage = 10;
            }

            // Para vista normal, usar paginación con el número de registros solicitado
            $items = $query->paginate($perPage)->withQueryString();

            // Verificar si hay datos
            if ($items->isEmpty()) {
                \Log::info('No se encontraron registros de graduados');
            } else {
                // Verificar el primer registro
                $firstItem = $items->first();
                if ($firstItem && $firstItem->person) {
                    \Log::info('Primer graduado:', [
                        'name' => $firstItem->person->name,
                        'lastname' => $firstItem->person->lastname,
                        'fullname' => $firstItem->person->fullname()
                    ]);
                }
            }

            return view('admin.pages.reports.graduates', compact('items'));
        } catch (\Exception $th) {
            \Log::error('Error en graduates: ' . $th->getMessage());
            throw $th;
        }
    }

    protected function exportToExcel($graduates)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $sheet->setCellValue('A1', 'Nombre');
        $sheet->setCellValue('B1', 'Cédula');
        $sheet->setCellValue('C1', 'Correo');
        $sheet->setCellValue('D1', 'Teléfono');
        $sheet->setCellValue('E1', 'Año Grado');
        $sheet->setCellValue('F1', 'Programa');
        $sheet->setCellValue('G1', 'Facultad');
        $sheet->setCellValue('H1', 'Universidad');
        $sheet->setCellValue('I1', 'Empresa Actual');
        $sheet->setCellValue('J1', 'Salario');

        // Datos
        $row = 2;
        foreach ($graduates as $graduate) {
            $person = $graduate->person;
            $academic = $person->personAcademic->first();
            $company = $person->personCompany->first();

            $sheet->setCellValue('A'.$row, $person->fullName());
            $sheet->setCellValue('B'.$row, $person->document);
            $sheet->setCellValue('C'.$row, $person->email);
            $sheet->setCellValue('D'.$row, $person->phone);
            $sheet->setCellValue('E'.$row, $academic ? $academic->year : 'N/A');
            $sheet->setCellValue('F'.$row, $academic ? $academic->program->name : 'N/A');
            $sheet->setCellValue('G'.$row, $academic ? $academic->program->faculty->name : 'N/A');
            $sheet->setCellValue('H'.$row, $academic ? $academic->program->faculty->university->name : 'N/A');
            $sheet->setCellValue('I'.$row, $company ? $company->company->name : 'N/A');
            $sheet->setCellValue('J'.$row, $company ? number_format($company->salary, 0, ',', '.') : 'N/A');
            
            $row++;
        }

        // Auto-size columns
        foreach(range('A','J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Crear archivo Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'graduados_'.date('Y-m-d').'.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    protected function exportToPdf($graduates)
    {
        $pdf = \PDF::loadView('admin.pages.reports.graduates-pdf', [
            'graduates' => $graduates
        ]);

        return $pdf->download('graduados_'.date('Y-m-d').'.pdf');
    }

    public function statistics()
    {
        try {
            /* Número total de pots */
            $posts = $this->postRepository->getTotalPosts()->count();
           // dd($pots);

            /** Graduados en el Extranjero */
            $extraGraduates = $this->personCompanyRepository->searchExtranjeroGraduates()->count();

            /** Cantidad de Graduados */
            $graduates = $this->userRepository->getByRole('graduado')->count();

            /** Salary MIN */
            $salaryMin = $this->personCompanyRepository->getSalary()->min('salary');

            /** Salary MAX */
            $salaryMax = $this->personCompanyRepository->getSalary()->max('salary');

            /** With Job */
            $graduadosConTrabajo = $this->personCompanyRepository->graduadosConTrabajo();

            /* Graduados sin Trabajo */
            $graduadoSinTrabajo = $graduates  -$graduadosConTrabajo;

            /** Countries */
            $countries = $this->countryRepository->all()->map(function ($map) {
                return $map->name;
            });

            /* Countries workings */

            $countriesWorking = $this->personCompanyRepository->graduatesByCountryName()->map(function ($map) {
                return $map->name;
            });

           /*  dd($countries); */
            /** Worker by Countries */
            $graduatesByCountry = $this->personCompanyRepository->graduatesByCountry();

            $arrayColors = $graduatesByCountry->map(function ($map) {
                return $this->faker->unique()->hexColor;
            });

            /** Graduates by Year */
            $graduatesByYear = $this->personAcademicRepository->graduatesByYear();

            $years = $graduatesByYear->map(function ($map) {
                return $map->year;
            });

            $graduatesByYearTotals = $graduatesByYear->map(function ($map) {
                return $map->total;
            });

            // return $years;

            return view('admin.pages.reports.statistics', compact(
                'extraGraduates',
                'graduates',
                'salaryMin',
                'salaryMax',
                'graduadosConTrabajo',
                'countries',
                'graduatesByCountry',
                'arrayColors',
                'years',
                'graduatesByYearTotals',
                'posts', 'graduadoSinTrabajo',
               'countriesWorking'
                
            ));
        } catch (\Exception $th) {
            throw $th;
        }
    }
}
