<?php

namespace App\Repositories;

use App\Models\Country;
use App\Repositories\AbstractRepository;

class CountryRepository extends AbstractRepository
{
    public function __construct(Country $model)
    {
        $this->model = $model;
    }
}
