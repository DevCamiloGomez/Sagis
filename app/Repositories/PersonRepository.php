<?php

namespace App\Repositories;

use App\Repositories\AbstractRepository;
use Illuminate\Support\Facades\Storage;
use App\Models\Person;

class PersonRepository extends AbstractRepository
{
    /** @var */
    protected $disk; // Will use default
    protected $folder = 'people';

    public function __construct(Person $model)
    {
        $this->model = $model;
        $this->disk = config('filesystems.default');
    }

    /**
     * @param string $file
     * @param string $fileName
     * 
     */
    public function saveImage(string $file, string $fileName)
    {
        $path = $this->folder . '/' . $fileName;
        Storage::disk($this->disk)->put($path, $file, 'public');
        return Storage::url($path);
    }

    /**
     * @param Person $person
     * 
     * @return void 
     */
    public function deleteImage(Person $person)
    {
        $path = $this->folder . '/' . $person->image;
        if (Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);
        }
    }

    /**
     * @param Person $loyalty
     * @param string $file
     * @param string $fileName
     * 
     * @return void
     */
    public function replaceImage(Person $person, string $file, string $fileName)
    {
        $this->deleteImage($person);
        return $this->saveImage($file, $fileName);
    }

    public function getByUserId() 
    {
        
    }

    public function getCantidadVerificados()
    {
        $table = $this->model->getTable();
        $query = $this->model
            ->select("{$table}.id")
            ->where("{$table}.has_data_person", true)
            ->where("{$table}.has_data_academic", true)
            ->where("{$table}.has_data_company", true);

        return $query
            ->groupBy("{$table}.id")
            ->get();
    }

    public function getOnlyGraduates()
    {
        $table = $this->model->getTable();
        $query = $this->model
            ->select("{$table}.id")
            ->where("{$table}.id", "!=", 1)
            ->where("{$table}.id", "!=", 2);
        return $query;
    }

    public function getOnlyGraduatesAll()
    {
        $table = $this->model->getTable();
        $query = $this->model
            ->select("*")
            ->where("{$table}.id", "!=", 1)
            ->where("{$table}.id", "!=", 2)
            ->whereNotNull("{$table}.email")
            ->where("{$table}.email", "!=", "");

        \Log::info('Consulta SQL para obtener graduados:', [
            'query' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        return $query->get();
    }
}
