<?php

namespace App\Repositories;

use App\Models\University;
use Illuminate\Support\Facades\DB;
use App\Repositories\AbstractRepository;

class UniversityRepository extends AbstractRepository
{
    public function __construct(University $model)
    {
        $this->model = $model;
    }

    public function getUniversity($univ_name)
    {
        $university = DB::table('universities')->whereRaw('LOWER(name) = LOWER(?)', [$univ_name])->value('name');
        return $university;
    }



    public function getUniversityID($univ_name)
    {
        $univeristy_id = DB::table('universities')->whereRaw('LOWER(name) = LOWER(?)', [$univ_name])->value('id');
        return $univeristy_id;
    }
}