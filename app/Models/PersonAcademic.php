<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase PersonAcademic - Modelo para gestión de información académica
 * 
 * Esta clase maneja la relación entre personas y sus estudios académicos,
 * permitiendo registrar múltiples programas cursados por una persona.
 * 
 * Características principales:
 * - Vinculación entre personas y programas académicos
 * - Registro del año de graduación
 * - Gestión de historial académico
 * 
 * @property int $id ID único del registro
 * @property int $person_id ID de la persona
 * @property int $program_id ID del programa académico
 * @property int $year Año de graduación
 * 
 * @property-read Program $program Programa académico relacionado
 * @property-read Collection|Person[] $people Personas relacionadas
 * 
 * Documentado por: Camilo Gomez   
 */
class PersonAcademic extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'person_academic';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['person_id', 'program_id', 'year'];


    /** Relation Methods */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function people()
    {
        return $this->hasMany(Person::class);
    }
}
