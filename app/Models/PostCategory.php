<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase PostCategory - Modelo para gestión de categorías de publicaciones
 * 
 * Esta clase maneja las diferentes categorías que pueden tener
 * las publicaciones en el sistema (noticias, eventos, etc.).
 * 
 * Características principales:
 * - Categorización de publicaciones
 * - Organización de contenido
 * - Filtrado de publicaciones
 * 
 * @property int $id ID único de la categoría
 * @property string $name Nombre de la categoría
 * @property string $description Descripción de la categoría
 * @property string $slug Identificador URL-friendly
 * 
 * @property-read Collection|Post[] $posts Publicaciones en esta categoría
 * 
 * Documentado por: Camilo Gomez
 */
class PostCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
