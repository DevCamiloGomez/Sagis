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
                            ->with(['program:id,name,faculty_id']);
                    },
                    'person.personCompany' => function($query) {
                        $query->where('in_working', true)
                            ->select('id', 'person_id', 'company_id', 'salary')
                            ->with(['company:id,name,country_id,city_id', 'company.country', 'company.city']);
                    }
                ]);

            // Aplicar búsqueda si existe
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->input('search');
                $query->whereHas('person', function($q) use ($searchTerm) {
                    $q->where(function($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('lastname', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('document', 'LIKE', "%{$searchTerm}%");
                    });
                });
            }

            // Filtros Avanzados
            if ($request->has('graduation_year') && !empty($request->graduation_year)) {
                $year = $request->graduation_year;
                $query->whereHas('person.personAcademic', function($q) use ($year) {
                    $q->where('year', $year);
                });
            }

            if ($request->has('working_status') && $request->working_status !== '') {
                $status = $request->working_status;
                if ($status == '1') { // Trabajando
                    $query->whereHas('person.personCompany', function($q) {
                        $q->where('in_working', true);
                    });
                } elseif ($status == '0') { // Desempleado
                    $query->whereDoesntHave('person.personCompany', function($q) {
                        $q->where('in_working', true);
                    });
                }
            }

            if ($request->has('min_salary') && !empty($request->min_salary)) {
                $minSalary = str_replace('.', '', $request->min_salary); // Remove basic formatting
                $query->whereHas('person.personCompany', function($q) use ($minSalary) {
                    $q->where('in_working', true)
                      ->where('salary', '>=', $minSalary);
                });
            }

            // Backwards compatibility for old 'filter' param if still used by dashboard cards
            if ($request->has('filter')) {
                $filter = $request->input('filter');
                if ($filter === 'unemployed') {
                    $query->whereDoesntHave('person.personCompany', function($q) {
                        $q->where('in_working', true);
                    });
                } elseif ($filter === 'foreign') {
                    $query->whereHas('person.personCompany', function($q) {
                        $q->where('in_working', true)
                          ->whereHas('company.country', function($q2) {
                              $q2->where('slug', '!=', 'co');
                          });
                    });
                }
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

            // Datos para filtros
            $years = $this->personAcademicRepository->graduatesByYear()->pluck('year')->sortDesc();

            // Paginación
            $perPage = $request->input('per_page', 10);
            $items = $query->paginate($perPage)->withQueryString();

            return view('admin.pages.reports.graduates', compact('items', 'years'));
        } catch (\Exception $th) {
            \Log::error('Error en graduates: ' . $th->getMessage());
            throw $th;
        }
    }

    public function exportToExcel($items)
    {
        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Graduados');

            // Headers
            $headers = ['Documento', 'Nombres', 'Apellidos', 'Correo', 'Teléfono', 'Celular', 'Dirección', 'Programa', 'Año', 'Trabaja', 'Empresa', 'Ciudad Empresa', 'Salario', 'País'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $sheet->getStyle($col . '1')->getFont()->setBold(true);
                $col++;
            }

            $row = 2;
            foreach ($items as $item) {
                $person = $item->person;
                $academic = $person->personAcademic->first();
                $company = $person->personCompany->where('in_working', true)->first();

                $sheet->setCellValue('A' . $row, $person->document);
                $sheet->setCellValue('B' . $row, $person->name);
                $sheet->setCellValue('C' . $row, $person->lastname);
                $sheet->setCellValue('D' . $row, $person->email);
                $sheet->setCellValue('E' . $row, $person->telephone);
                $sheet->setCellValue('F' . $row, $person->phone);
                $sheet->setCellValue('G' . $row, $person->address);
                $sheet->setCellValue('H' . $row, $academic ? $academic->program->name : '');
                $sheet->setCellValue('I' . $row, $academic ? $academic->year : '');
                $sheet->setCellValue('J' . $row, $company ? 'SI' : 'NO');
                $sheet->setCellValue('K' . $row, $company ? $company->company->name : '');
                $sheet->setCellValue('L' . $row, ($company && $company->company->city) ? $company->company->city->name : '');
                $sheet->setCellValue('M' . $row, $company ? $company->salary : '');
                $sheet->setCellValue('N' . $row, ($company && $company->company->country) ? $company->company->country->name : '');

                $row++;
            }

            foreach (range('A', 'N') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'graduados_' . date('Y-m-d') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            return back()->with('error', 'Error al exportar Excel: ' . $e->getMessage());
        }
    }

    public function exportToPdf($items)
    {
        try {
            $graduates = $items;
            $pdf = \PDF::loadView('admin.pages.reports.graduates-pdf', compact('graduates'));
            return $pdf->download('graduados_' . date('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al exportar PDF: ' . $e->getMessage());
        }
    }

    public function statistics()
    {
        try {
            /* Número total de pots */
            $posts = $this->postRepository->getTotalPosts()->count();

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

            /** New Stats */
            $salaryDistribution = $this->personCompanyRepository->getSalaryDistribution();
            $salaryLabels = $salaryDistribution->keys();
            $salaryValues = $salaryDistribution->values();

            $topCompanies = $this->personCompanyRepository->getTopHiringCompanies();
            $topCompaniesLabels = $topCompanies->pluck('name');
            $topCompaniesValues = $topCompanies->pluck('total');

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
                'posts', 
                'graduadoSinTrabajo',
                'countriesWorking',
                'salaryLabels',
                'salaryValues',
                'topCompaniesLabels',
                'topCompaniesValues'
            ));
        } catch (\Exception $th) {
            throw $th;
        }
    }
    public function exportStatisticsPdf(Request $request)
    {
        try {
            // Get data
            $posts = $this->postRepository->getTotalPosts()->count();
            $extraGraduates = $this->personCompanyRepository->searchExtranjeroGraduates()->count();
            $graduates = $this->userRepository->getByRole('graduado')->count();
            $salaryMin = $this->personCompanyRepository->getSalary()->min('salary');
            $salaryMax = $this->personCompanyRepository->getSalary()->max('salary');
            $graduadosConTrabajo = $this->personCompanyRepository->graduadosConTrabajo();
            $graduadoSinTrabajo = $graduates - $graduadosConTrabajo;
            
            $salaryDistribution = $this->personCompanyRepository->getSalaryDistribution();
            $topCompanies = $this->personCompanyRepository->getTopHiringCompanies();
            $graduatesByYear = $this->personAcademicRepository->graduatesByYear();

            // Get charts images from request
            $charts = [
                'salary' => $request->input('salary_chart'),
                'companies' => $request->input('companies_chart'),
                'pie' => $request->input('pie_chart'),
                'bar' => $request->input('bar_chart'),
            ];

            $pdf = \PDF::loadView('admin.pages.reports.statistics-pdf', compact(
                'posts', 'extraGraduates', 'graduates', 'salaryMin', 'salaryMax',
                'graduadosConTrabajo', 'graduadoSinTrabajo', 'salaryDistribution',
                'topCompanies', 'graduatesByYear', 'charts'
            ));

            return $pdf->download('reporte_estadistico_'.date('Y-m-d').'.pdf');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al exportar PDF: ' . $e->getMessage());
        }
    }
}
