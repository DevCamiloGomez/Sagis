<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Clase PostImage - Modelo para gestión de imágenes de publicaciones
 * 
 * Esta clase maneja las imágenes asociadas a las publicaciones del sistema,
 * permitiendo almacenar y gestionar múltiples imágenes por publicación.
 * 
 * Características principales:
 * - Almacenamiento de imágenes de publicaciones
 * - Gestión de imagen principal (header)
 * - Manejo de URLs y assets
 * - Relaciones con publicaciones
 * 
 * @property int $id ID único de la imagen
 * @property int $post_id ID de la publicación relacionada
 * @property string $asset_url URL de la imagen
 * @property string $asset Nombre del archivo de imagen
 * @property boolean $is_header Indica si es la imagen principal
 * 
 * @property-read Post $post Publicación relacionada
 * 
 * Documentado por: Camilo Gomez
 */
class PostImage extends Model
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
        if (filter_var($this->asset_url, FILTER_VALIDATE_URL)) {
            return $this->asset_url;
        }
        return Storage::disk('s3')->url('posts/' . $this->asset);
    }
}
