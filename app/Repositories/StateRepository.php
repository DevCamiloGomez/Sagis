<?php

namespace App\Repositories;

use App\Models\State;
use Illuminate\Support\Facades\DB;
use App\Repositories\AbstractRepository;

class StateRepository extends AbstractRepository
{
    public function __construct(State $model)
    {
        $this->model = $model;
    }

    public function getEstado($state_name)
    {
        $state = DB::table('states')->whereRaw('LOWER(name) = LOWER(?)', [$state_name])->value('name');
        return $state;
    }

    public function getStateID($state_name)
    {
        $state_id = DB::table('states')->whereRaw('LOWER(name) = LOWER(?)', [$state_name])->value('id');
        return $state_id;
    }
}