<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase PostDocument - Modelo para gestión de documentos adjuntos a publicaciones
 * 
 * Esta clase maneja los documentos que pueden ser adjuntados
 * a las publicaciones del sistema (PDFs, documentos, etc.).
 * 
 * Características principales:
 * - Almacenamiento de documentos
 * - Gestión de tipos de archivos
 * - Manejo de URLs y assets
 * - Relaciones con publicaciones
 * 
 * @property int $id ID único del documento
 * @property int $post_id ID de la publicación relacionada
 * @property string $asset_url URL del documento
 * @property string $asset Nombre del archivo
 * @property string $type Tipo de documento
 * 
 * @property-read Post $post Publicación relacionada
 * 
 * Documentado por: Camilo Gomez
 */
class PostDocument extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['post_id', 'asset_url', 'asset', 'is_header'];

    /**
     * Get the Post
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the full asset
     * 
     * @return string
     */
    public function fullAsset()
    {
        return $this->asset_url . $this->asset;
    }
}
