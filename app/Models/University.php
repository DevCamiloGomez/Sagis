<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase University - Modelo para gestión de universidades
 * 
 * Esta clase maneja la información de las instituciones universitarias
 * registradas en el sistema.
 * 
 * Características principales:
 * - Gestión de información institucional
 * - Relación con ubicaciones geográficas
 * - Vinculación con facultades
 * - Seguimiento de programas académicos
 * 
 * @property int $id ID único de la universidad
 * @property int $city_id ID de la ciudad donde se ubica
 * @property string $name Nombre de la universidad
 * @property string $address Dirección física
 * @property string $website Sitio web institucional
 * 
 * @property-read City $city Ciudad donde se ubica
 * @property-read Collection|Faculty[] $faculties Facultades de la universidad
 * 
 * Documentado por: Camilo Gomez
 */
class University extends Model
{
    use HasFactory;


/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['city_id', 'name', 'address'];


    /** Relation Methods */
    public function faculties()
    {
        return $this->hasMany(Faculty::class);
    }

}
