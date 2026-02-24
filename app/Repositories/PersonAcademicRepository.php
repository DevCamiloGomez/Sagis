<?php

namespace App\Repositories;

use App\Models\PersonAcademic;
use App\Repositories\AbstractRepository;
use Illuminate\Support\Facades\DB;

class PersonAcademicRepository extends AbstractRepository
{
    public function __construct(PersonAcademic $model)
    {
        $this->model = $model;
    }

    public function graduatesByYear()
    {
        $joinPrograms = "programs";
        $query = $this->model
            ->select(['person_academic.year', DB::raw('count(person_academic.person_id) AS total')])
            ->join("{$joinPrograms}", "person_academic.program_id", "{$joinPrograms}.id")
            ->where("{$joinPrograms}.id", 1);

        return $query
            // ->groupBy('person_academic.person_id')
            ->groupBy('person_academic.year')
            ->orderBy('person_academic.year', 'ASC')
            ->get();
    }

    public function getGraduatesPost(){

        $table = $this->model->getTable();
        $joinPrograms = "programs";
        $joinAcademicLevels = "academic_levels";
        $query = $this->model
            ->select("{$table}.person_id")
            ->join("{$joinPrograms}", "{$table}.program_id", "{$joinPrograms}.id")
            ->join("{$joinAcademicLevels}", "{$joinPrograms}.academic_level_id", "{$joinAcademicLevels}.id")
            ->whereRaw("LOWER({$joinAcademicLevels}.name) != ?", ['pregrado']);

            return $query->groupBy("{$table}.person_id")
            ->get();
    }


    public function getUni()
    {
        /* return $this->model
            ->with('program')->get(); */

            $u = DB::table('universities')->get();
        return $u;
    }

    public function getProgramas()
    {
        /* return $this->model
            ->with('program')->get(); */

            $p = DB::table('programs')->get();
        return $p;
    }

    public function getNiveles()
    {
        /* return $this->model
            ->with('program')->get(); */

            $n = DB::table('academic_levels')->get();
        return $n;
    }
}
