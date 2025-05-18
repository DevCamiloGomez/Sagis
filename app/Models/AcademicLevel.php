<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase AcademicLevel - Modelo para gestión de niveles académicos
 * 
 * Esta clase maneja los diferentes niveles académicos disponibles
 * en el sistema (pregrado, posgrado, maestría, doctorado, etc.).
 * 
 * Características principales:
 * - Catálogo de niveles académicos
 * - Relación con programas académicos
 * - Gestión de jerarquía académica
 * 
 * @property int $id ID único del nivel académico
 * @property string $name Nombre del nivel académico
 * @property string $description Descripción del nivel
 * 
 * @property-read Collection|Program[] $programs Programas de este nivel académico
 * 
 * Documentado por: Camilo Gomez
 */
class AcademicLevel extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];



     /** Relation Methods */
     public function program()
     {
        return $this->belongsToMany(Program::class);
     }
}

