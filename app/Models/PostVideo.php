<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase PostVideo - Modelo para gestión de videos de publicaciones
 * 
 * Esta clase maneja los videos asociados a las publicaciones del sistema,
 * permitiendo almacenar y gestionar videos de YouTube por publicación.
 * 
 * Características principales:
 * - Almacenamiento de videos de publicaciones
 * - Gestión de video principal (header)
 * - Manejo de URLs de YouTube
 * - Relaciones con publicaciones
 * 
 * @property int $id ID único del video
 * @property int $post_id ID de la publicación relacionada
 * @property string $asset_url ID del video de YouTube
 * @property boolean $is_header Indica si es el video principal
 * 
 * @property-read Post $post Publicación relacionada
 * 
 * Documentado por: Camilo Gomez
 */
class PostVideo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['post_id', 'asset_url', 'is_header'];

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
        return "https://www.youtube.com/embed/" . $this->asset_url;
    }
}
