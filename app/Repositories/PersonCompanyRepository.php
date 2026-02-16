<?php

namespace App\Repositories;

use App\Models\PersonCompany;
use App\Repositories\AbstractRepository;
use Illuminate\Support\Facades\DB;

class PersonCompanyRepository extends AbstractRepository
{
    public function __construct(PersonCompany $model)
    {
        $this->model = $model;
    }

    public function searchExtranjeroGraduates()
    {
        $query = $this->model
            ->join('people', 'people.id', 'person_company.person_id')
            ->join('companies', 'companies.id', 'person_company.company_id')
            ->join('cities', 'cities.id', 'companies.city_id')
            ->join('states', 'states.id', 'cities.state_id')
            ->join('countries', 'countries.id', 'states.country_id')

            ->select('person_company.person_id');

        

        $query->where('person_company.in_working', true );
        $query->where('countries.slug', '!=', 'co');
      /*   $query->where('countries.slug','like','%co%'); */
        


        return $query
            ->groupBy('person_company.person_id')
            ->get();
    }


    public function getGraduadosTrabajanEnColombia()
    {
        $query = $this->model
            ->join('people', 'people.id', 'person_company.person_id')
            ->join('companies', 'companies.id', 'person_company.company_id')
            ->join('cities', 'cities.id', 'companies.city_id')
            ->join('states', 'states.id', 'cities.state_id')
            ->join('countries', 'countries.id', 'states.country_id')

            ->select('person_company.person_id');

        

        $query->where('person_company.in_working', true );
        $query->where('countries.slug','like','%co%'); 
        


        return $query
            ->groupBy('person_company.person_id')
            ->get();
    }

    public function getSalary()
    {
        $query = $this->model
            ->select(['person_company.person_id', 'person_company.salary'])
            ->where('person_company.in_working', true)
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('person_company')
                    ->where('in_working', true)
                    ->groupBy('person_id');
            });

        return $query->get();
    }

    public function graduadosConTrabajo()
    {
        $query = $this->model
            ->select('person_company.person_id');

        return $query
            ->whereIn('person_id', function ($q) {
                return $q
                    ->select('person_company.person_id')
                    ->from('person_company')
                    ->where('person_company.in_working', true)
                    ->orderBy('person_company.person_id');
            })
            ->groupBy('person_company.person_id')
            ->get()->count();
    }

    public function graduatesByCountry()
    {
        $query = $this->model
            ->select([
                'countries.id',
                DB::raw('count(person_company.person_id) AS total')
            ])
            ->join('companies', 'companies.id', 'person_company.company_id')
            ->join('cities', 'cities.id', 'companies.city_id')
            ->join('states', 'states.id', 'cities.state_id')
            ->join('countries', 'countries.id', 'states.country_id');

        $query->where('person_company.in_working', true);

        return $query
            ->groupBy('countries.id')
          /*   ->orderby('countries.id', 'ASC') */
         /*  ->get(); */
            ->get()->map(function ($map)  {
             
            

              /*   return [$map['id'] => $map['total']]; */
                /* $map->id; */
                return $map->total;

                
            });

          /*   ->get()->map(function ($map){
                return [
                    'id' => $map->id,
                    'total' => $map->total
                ];
            }); */
    }

    public function graduatesByCountryName()
    {
        $query = $this->model
            ->select([
                'countries.name',
                DB::raw('count(person_company.person_id) AS total')
            ])
            ->join('companies', 'companies.id', 'person_company.company_id')
            ->join('cities', 'cities.id', 'companies.city_id')
            ->join('states', 'states.id', 'cities.state_id')
            ->join('countries', 'countries.id', 'states.country_id');

        $query->where('person_company.in_working', true);

        return $query
            ->groupBy('countries.id', 'countries.name')
          ->get();
    }

    public function getCompanies(){
        $c = DB::table('companies')->get();
        return $c;
    }

    public function getSalaryDistribution()
    {
        // Obtener salarios actuales
        $salaries = $this->getSalary();

        $distribution = [
            'Menos de 2M' => 0,
            '2M - 4M' => 0,
            '4M - 6M' => 0,
            'Más de 6M' => 0
        ];

        foreach ($salaries as $record) {
            $salary = $record->salary;
            if ($salary < 2000000) {
                $distribution['Menos de 2M']++;
            } elseif ($salary >= 2000000 && $salary < 4000000) {
                $distribution['2M - 4M']++;
            } elseif ($salary >= 4000000 && $salary <= 6000000) {
                $distribution['4M - 6M']++;
            } else {
                $distribution['Más de 6M']++;
            }
        }

        return collect($distribution);
    }

    public function getTopHiringCompanies($limit = 5)
    {
        $query = $this->model
            ->select([
                'companies.name',
                DB::raw('count(person_company.person_id) as total')
            ])
            ->join('companies', 'companies.id', 'person_company.company_id')
            ->where('person_company.in_working', true)
            ->groupBy('companies.id', 'companies.name')
            ->orderByDesc('total')
            ->limit($limit);

        return $query->get();
    }
}
