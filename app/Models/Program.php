<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase Program - Modelo para gestión de programas académicos
 * 
 * Esta clase maneja la información de los programas académicos ofrecidos
 * por las diferentes facultades de la universidad.
 * 
 * Características principales:
 * - Gestión de programas académicos
 * - Relación con facultades
 * - Relación con niveles académicos
 * - Vinculación con información académica de personas
 * 
 * @property int $id ID único del programa
 * @property int $faculty_id ID de la facultad a la que pertenece
 * @property int $academic_level_id Nivel académico del programa
 * @property string $name Nombre del programa
 * 
 * @property-read Faculty $faculty Facultad relacionada
 * @property-read AcademicLevel $academicLevel Nivel académico relacionado
 * @property-read Collection|PersonAcademic[] $personAcademics Personas vinculadas al programa
 * 
 * Documentado por: Camilo Gomez
 */
class Program extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['faculty_id', 'academic_level_id', 'name'];



    /** Relation Methods */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function academicLevel()
    {
        return $this->belongsTo(AcademicLevel::class);
    }

    public function personAcademics()
    {
        return $this->hasMany(PersonAcademic::class);
    }
}
