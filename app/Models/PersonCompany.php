<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase PersonCompany - Modelo para gestión de experiencia laboral
 * 
 * Esta clase maneja la relación entre personas y empresas,
 * permitiendo registrar la experiencia laboral de los graduados.
 * 
 * Características principales:
 * - Registro de experiencia laboral
 * - Control de períodos de trabajo
 * - Seguimiento de salarios
 * - Estado laboral actual
 * 
 * @property int $id ID único del registro
 * @property int $person_id ID de la persona
 * @property int $company_id ID de la empresa
 * @property boolean $in_working Indica si actualmente trabaja en la empresa
 * @property date $start_date Fecha de inicio
 * @property date $end_date Fecha de finalización
 * @property decimal $salary Salario
 * 
 * @property-read Company $company Empresa relacionada
 * @property-read Collection|Person[] $people Personas relacionadas
 * 
 * Documentado por: Camilo Gomez
 */
class PersonCompany extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'person_company';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['person_id', 'company_id', 'in_working', 'start_date', 'end_date', 'salary'];


    /** Relation Methods */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function people()
    {
        return $this->hasMany(Person::class);
    }
}
