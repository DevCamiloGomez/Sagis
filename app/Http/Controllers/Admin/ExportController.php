<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController extends Controller
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function graduatesExcel()
    {
        try {
            $graduateRole = $this->roleRepository->getByAttribute('name', 'graduado');
            
            // Obtener datos con eager loading optimizado
            $graduates = $graduateRole->users()
                ->with([
                    'person' => function($query) {
                        $query->select('id', 'document', 'email', 'phone')
                            ->with(['birthdatePlace:id,name']);
                    },
                    'person.personAcademic' => function($query) {
                        $query->select('id', 'person_id', 'program_id', 'year')
                            ->with(['program:id,name,faculty_id', 'program.faculty:id,name,university_id', 'program.faculty.university:id,name']);
                    },
                    'person.personCompany' => function($query) {
                        $query->where('in_working', true)
                            ->select('id', 'person_id', 'company_id', 'salary')
                            ->with(['company:id,name']);
                    }
                ])
                ->get();

            // Crear nuevo spreadsheet
            $spreadsheet = new Spreadsheet();
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
            $writer = new Xlsx($spreadsheet);
            $filename = 'graduados_'.date('Y-m-d').'.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            return back()->with('error', 'Error al exportar datos: '.$e->getMessage());
        }
    }
} 