<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CarouselImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'type',
        'order',
        'is_active',
        'asset_url',
        'asset'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    public function fullAsset()
    {
        if (filter_var($this->asset_url, FILTER_VALIDATE_URL)) {
            return $this->asset_url;
        }
        return Storage::url('carousel/' . $this->asset);
    }

    public static function getMainCarousel()
    {
        try {
            return self::where('type', 'main')
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        } catch (\Exception $e) {
            Log::warning('Error obteniendo carousel principal: ' . $e->getMessage());
            return collect();
        }
    }

    public static function getSectionCarousel()
    {
        try {
            return self::where('type', 'section')
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        } catch (\Exception $e) {
            Log::warning('Error obteniendo carousel de sección: ' . $e->getMessage());
            return collect();
        }
    }
} 