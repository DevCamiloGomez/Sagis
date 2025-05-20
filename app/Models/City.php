<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase City - Modelo para gestión de ciudades
 * 
 * Esta clase maneja la información de las ciudades y su relación
 * con estados/departamentos y países en el sistema.
 * 
 * Características principales:
 * - Gestión de información geográfica
 * - Relación jerárquica con estados y países
 * - Vinculación con universidades y empresas
 * - Gestión de ubicaciones de personas
 * 
 * @property int $id ID único de la ciudad
 * @property int $state_id ID del estado/departamento
 * @property string $name Nombre de la ciudad
 * @property string $slug Código identificador de la ciudad
 * 
 * @property-read State $state Estado/departamento al que pertenece
 * @property-read Collection|University[] $universities Universidades en esta ciudad
 * @property-read Collection|Company[] $companies Empresas en esta ciudad
 * @property-read Collection|Person[] $birthPeople Personas nacidas en esta ciudad
 * 
 * Documentado por: Camilo Gomez
 */
class City extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['state_id', 'name', 'slug', 'geoname_id'];




    /** Relation Methods */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->hasOneThrough(Country::class, State::class, 'country_id', 'id', 'state_id');
    }
}
