<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase DocumentType - Modelo para gestión de tipos de documentos
 * 
 * Esta clase maneja los diferentes tipos de documentos de identificación
 * que pueden ser utilizados en el sistema.
 * 
 * Características principales:
 * - Catálogo de tipos de documentos
 * - Validación de documentos
 * - Relación con personas
 * 
 * @property int $id ID único del tipo de documento
 * @property string $name Nombre del tipo de documento
 * @property string $description Descripción del tipo de documento
 * @property string $code Código identificador del tipo de documento
 * 
 * @property-read Collection|Person[] $people Personas que usan este tipo de documento
 * 
 * Documentado por: Camilo Gomez
 */
class DocumentType extends Model
{
    use HasFactory;


/**
 * The attributes that are mass assignable.
 *
 * @var array
 */
protected $fillable = ['name', 'description', 'slug'];

/**
 * Get the DocumentType's name.
 *
 * @param  string  $value
 * @return string
 */
public function getNameAttribute($value)
{
    return ucwords($value);
}

/** Relation Methods */
public function people()
{
    return $this->hasMany(Person::class);
}
}