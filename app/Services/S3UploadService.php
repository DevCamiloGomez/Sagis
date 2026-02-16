<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class S3UploadService
{
    /**
     * Sube un archivo al disco configurado por defecto y retorna la URL
     *
     * @param $file
     * @param string $folder
     * @return array
     */
    public function uploadFile($file, $folder = 'general')
    {
        try {
            // Generar un nombre único para el archivo
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            
            // Subir el archivo usando el disco por defecto
            $path = Storage::putFileAs($folder, $file, $fileName, 'public');
            
            // Obtener la URL
            $url = Storage::url($path);
            
            return [
                'url' => $url,
                'path' => $path,
                'filename' => $fileName
            ];
        } catch (\Exception $e) {
            \Log::error('Error al subir archivo: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Elimina un archivo del disco configurado
     *
     * @param string $path
     * @return bool
     */
    public function deleteFile($path)
    {
        try {
            return Storage::delete($path);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar archivo: ' . $e->getMessage());
            return false;
        }
    }
}
