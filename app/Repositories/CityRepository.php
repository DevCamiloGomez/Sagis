<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Support\Facades\DB;
use App\Repositories\AbstractRepository;

class CityRepository extends AbstractRepository
{
    public function __construct(City $model)
    {
        $this->model = $model;
    }

    public function allOrderBy(string $order)
    {
        return $this->model
            ->with('state')->get();
    }

    public function getCity($city_name)
    {
        return $this->model->where('name', $city_name)->first();
    }

    public function getCityID($city_name)
    {
            $city_id = DB::table('cities')->where('name', $city_name)->value('id');
        return $city_id;
    }

    public function getByGeonameId($geonameId)
    {
        return $this->model->where('geoname_id', $geonameId)->first();
    }
}
