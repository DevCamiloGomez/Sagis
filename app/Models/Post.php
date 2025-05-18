<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * Get the description with clickable links
     * 
     * @return string
     */
    public function getDescriptionWithLinks()
    {
        $pattern = '/(https?:\/\/[^\s]+)/';
        return preg_replace($pattern, '<a href="$1" target="_blank">$1</a>', $this->description);
    }

}
