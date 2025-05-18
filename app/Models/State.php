<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase State - Modelo para gestión de estados/departamentos
 * 
 * Esta clase maneja la información de los estados o departamentos
 * y su relación con países y ciudades en el sistema.
 * 
 * Características principales:
 * - Gestión de información geográfica regional
 * - Relación jerárquica con países
 * - Relación con ciudades
 * - Validación de ubicaciones
 * 
 * @property int $id ID único del estado/departamento
 * @property int $country_id ID del país al que pertenece
 * @property string $name Nombre del estado/departamento
 * @property string $slug Código identificador del estado
 * 
 * @property-read Country $country País al que pertenece
 * @property-read Collection|City[] $cities Ciudades del estado/departamento
 * 
 * Documentado por: Camilo Gomez
 */
class State extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['country_id', 'name', 'slug'];





     /** Relation Methods */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
