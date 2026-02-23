<?php

namespace App\Repositories;

use App\Models\Country;
use Illuminate\Support\Facades\DB;
use App\Repositories\AbstractRepository;

class CountryRepository extends AbstractRepository
{
    public function __construct(Country $model)
    {
        $this->model = $model;
    }



    public function getPais($pais_name)
    {
        $pais = DB::table('countries')->whereRaw('LOWER(name) = LOWER(?)', [$pais_name])->value('name');
        return $pais;
    }



    public function getPaisID($pais_name)
    {
        $pais_id = DB::table('countries')->whereRaw('LOWER(name) = LOWER(?)', [$pais_name])->value('id');
        return $pais_id;
    }

}