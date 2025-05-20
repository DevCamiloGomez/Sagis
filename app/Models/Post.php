<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase Post - Modelo para gestión de publicaciones
 * 
 * Esta clase maneja las publicaciones y noticias del sistema,
 * incluyendo la gestión de contenido multimedia asociado.
 * 
 * Características principales:
 * - Gestión de publicaciones y noticias
 * - Manejo de imágenes múltiples
 * - Manejo de videos
 * - Manejo de documentos adjuntos
 * - Categorización de contenido
 * 
 * @property int $id ID único de la publicación
 * @property int $post_category_id ID de la categoría
 * @property string $title Título de la publicación
 * @property string $description Contenido de la publicación
 * @property date $date Fecha de la publicación
 * 
 * @property-read PostCategory $postCategory Categoría de la publicación
 * @property-read Collection|PostImage[] $images Imágenes asociadas
 * @property-read Collection|PostVideo[] $videos Videos asociados
 * @property-read Collection|PostDocument[] $documents Documentos asociados
 * 
 * Documentado por: Camilo Gomez
 */
class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['post_category_id', 'title', 'description', 'date'];


    /**
     * Get Post Category
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function postCategory()
    {
        return $this->belongsTo(PostCategory::class);
    }

    /**
     * Get Images
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    /**
     * Get Images
     * 
     * @return \App\Models\PostImage
     */
    public function imageHeader()
    {
        return $this->images()->where('is_header', true)->first();
    }

    public function getCountimage()
    {
        return $this->images()->count();
    }

    public function getCountVideo()
    {
        return $this->videos()->count();
    }

    public function is_imageOne()
    {
        return $this->images()->count()== 1? true: false;
    }

    /**
     * Get Images
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos()
    {
        return $this->hasMany(PostVideo::class);
    }

    /**
     * Get Images
     * 
     * @return \App\Models\PostVideo
     */
    public function videoHeader()
    {
        return $this->videos()->where('is_header', true)->first();
    }


    /**
     * Get Images
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(PostDocument::class);
    }

    /**
     * Get if the post has images
     * 
     * @return bool
     */
    public function hasImages()
    {
        return $this->images()->count() > 0 ? true : false;
    }

    /**
     * Get if the post has videos
     * 
     * @return bool
     */
    public function hasVideos()
    {
        return $this->videos()->count() > 0 ? true : false;
    }

    /**
     * Get if the post has documents
     * 
     * @return bool
     */
    public function hasDocuments()
    {
        return $this->documents()->count() > 0 ? true : false;
    }

}
