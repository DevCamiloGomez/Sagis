<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase Faculty - Modelo para gestión de facultades universitarias
 * 
 * Esta clase maneja la información de las facultades académicas
 * asociadas a las universidades en el sistema.
 * 
 * Características principales:
 * - Gestión de facultades
 * - Relación con universidades
 * - Relación con programas académicos
 * 
 * @property int $id ID único de la facultad
 * @property int $university_id ID de la universidad relacionada
 * @property string $name Nombre de la facultad
 * 
 * @property-read University $university Universidad relacionada
 * @property-read Collection|Program[] $programs Programas académicos de la facultad
 * 
 * Documentado por: Camilo Gomez
 */
class Faculty extends Model
{
    use HasFactory;



/**
 * The attributes that are mass assignable.
 *
 * @var array
 */
protected $fillable = ['university_id', 'name'];


 /** Relation Methods */
public function university ()
{
    return $this->belongsTo(University::class);
}

public function programs()
{
    return $this->hasMany(Program::class);
}
}