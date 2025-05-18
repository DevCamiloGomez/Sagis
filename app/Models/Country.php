<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Clase Country - Modelo para gestión de países
 * 
 * Esta clase maneja la información de los países y sus relaciones
 * con estados/departamentos y ciudades en el sistema.
 * 
 * Características principales:
 * - Gestión de información geográfica nacional
 * - Relación con estados/departamentos
 * - Validación de ubicaciones
 * - Gestión de códigos internacionales
 * 
 * @property int $id ID único del país
 * @property string $name Nombre del país
 * @property string $slug Código identificador del país
 * @property string $phone_code Código telefónico internacional
 * @property string $currency Moneda oficial
 * @property string $currency_symbol Símbolo de la moneda
 * 
 * @property-read Collection|State[] $states Estados/departamentos del país
 * @property-read Collection|City[] $cities Ciudades del país (a través de states)
 * 
 * Documentado por: Camilo Gomez
 */
class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug'];


    /**
     * Set the Country's name.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        return $this->attributes['name'] = Str::lower($value);
    }

    /**
     * Get the Country's name. $county->name; Colombia.
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    /**
     * Get the Country's name in MAYUS. $country->name_mayus = COLOMBIA.
     *
     * @param  string  $value
     * @return string
     */
    // public function getNameMayusAttribute($value)
    // {
    //     return Str::upper($this->attributes['name']);
    // }


    
    /** Relation Methods */
    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function cities()
    {
        return $this->hasManyThrough(City::class, State::class);
    }
}
