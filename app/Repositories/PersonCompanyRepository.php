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
            ->where('person_company.in_Working', true)
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
                DB::raw('countries.id, count(person_company.person_id) AS total')
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
                DB::raw('countries.name, count(person_company.person_id) AS total')
            ])
            ->join('companies', 'companies.id', 'person_company.company_id')
            ->join('cities', 'cities.id', 'companies.city_id')
            ->join('states', 'states.id', 'cities.state_id')
            ->join('countries', 'countries.id', 'states.country_id');

        $query->where('person_company.in_working', true);

        return $query
            ->groupBy('countries.id')
          ->get();
    }

    public function getCompanies(){
        $c = DB::table('companies')->get();
        return $c;
    }
}
