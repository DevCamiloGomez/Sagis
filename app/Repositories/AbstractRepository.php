<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Exception;

class DuplicatedRecordException extends Exception
{

    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct('The record already exists in the database.', $code, $previous);
    }
}

class AbstractRepository
{

    /**
     * @var Model $model
     */
    protected $model;

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    function all()
    {
        return $this->model->all();
    }

    /**
     * @return Model $model
     */
    public function randomFirst()
    {
        return $this->model->all()->random(1)->first();
    }

    /**
     * @return static
     */
    function newInstance()
    {
        return $this->model->newInstance();
    }

    /**
     * @param $id
     *
     * @return Model
     */
    function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param string $attribute
     * @param $value
     *
     * @return Model
     */
    function getByAttribute($attribute, $value)
    {
        return $this->model->where($attribute, $value)->first();
    }

    function find($id, $columns = array('*'))
    {
        return $this->model->find($id, $columns);
    }

    function first()
    {
        return $this->model->first();
    }

    /**
     * @param int $count
     * @param array $params
     * 
     */
    public function createFactory(int $count = 1, array $params = [])
    {
        return $this->model->factory()->count($count)->create($params);
    }

    /**
     * @param array $attributes
     *
     * @return static
     */
    function firstOrCreate(array $attributes = [])
    {
        return $this->model->firstOrCreate($attributes);
    }

    /**
     * @param array $search
     * @param array $attributes
     *
     * @return Model
     */
    function updateOrCreate(array $search = [], array $attributes = [])
    {
        return $this->model->updateOrCreate($search, $attributes);
    }

    /**
     * @param array $input
     *
     * @return static
     * @throws DuplicatedRouteException
     * @throws Exception
     * @throws \Exception
     */
    function create(array $input)
    {
        try {
            return $this->model->create($input);
        }
        catch (Exception $e) {
            // Si es un error de duplicación, intentamos crear el registro de nuevo
            if ($e->getCode() == '23000') {
                // Intentamos crear el registro con un nuevo ID
                return $this->model->create($input);
            }
            throw $e;
        }
    }

    /**
     * @param array $input
     *
     * @return static
     */
    function forceCreate(array $input)
    {
        $this->model->unguard();
        $instance = $this->model->create($input);
        $this->model->reguard();

        return $instance;
    }

    /**
     * @throws \Exception
     */
    public function deleteAll()
    {
        $this->model->delete();
    }

    /**
     * @param Model $model
     *
     * @return mixed
     */
    public function save(Model $model)
    {
        return $model->save();
    }

    /**
     * @param Model $model
     * @param array $attributes
     * @return bool
     */
    public function update(Model $model, array $attributes)
    {
        return $model->update($attributes);
    }

    /**
     * Delete an Eloquent Model from database
     *
     * @param Model $model
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Model $model)
    {
        return $model->delete();
    }

    /**
     * @param Model $model
     *
     * @return void
     */
    public function forceDelete(Model $model)
    {
        return $model->forceDelete();
    }

    /**
     * Truncate model table
     */
    public function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->model->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1 ;');
    }

    public function getModelName($name)
    {
        $modelName = $this->model->whereRaw('LOWER(name) = LOWER(?)', [$name])->value('name');
        return $modelName;
    }



    public function getModelID($name)
    {
        $model_id = $this->model->whereRaw('LOWER(name) = LOWER(?)', [$name])->latest()->value('id');
        return $model_id;
    }

    public function last()
    {
        return $this->model->latest()->first()->id;
    }
}