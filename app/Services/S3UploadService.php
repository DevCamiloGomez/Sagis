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
            $extension  = strtolower($file->getClientOriginalExtension());
            $mimeType   = $file->getMimeType();
            $isImage    = str_starts_with($mimeType, 'image/');

            // Si es imagen, comprimir con GD antes de guardar
            if ($isImage) {
                $fileName   = time() . '_' . uniqid() . '.jpg';
                $compressed = $this->compressImage($file->getRealPath(), $mimeType);
                $path       = $folder . '/' . $fileName;

                Storage::disk('public')->put($path, $compressed);
            } else {
                $fileName = time() . '_' . uniqid() . '.' . $extension;
                $path     = Storage::disk('public')->putFileAs($folder, $file, $fileName);
            }

            $url = Storage::disk('public')->url($path);

            return [
                'url'      => $url,
                'path'     => $path,
                'filename' => $fileName,
            ];
        } catch (\Throwable $e) {
            \Log::error('Error al subir archivo: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Comprime y redimensiona una imagen usando GD.
     * Máximo 1920px de ancho, calidad JPEG 80%.
     */
    private function compressImage(string $sourcePath, string $mimeType): string
    {
        $maxWidth = 1920;
        $quality  = 80;

        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $src = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $src = imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $src = imagecreatefromwebp($sourcePath);
                break;
            case 'image/gif':
                $src = imagecreatefromgif($sourcePath);
                break;
            default:
                // Formato no soportado: devolver contenido tal cual
                return file_get_contents($sourcePath);
        }

        $origW = imagesx($src);
        $origH = imagesy($src);

        // Solo redimensionar si supera el ancho máximo
        if ($origW > $maxWidth) {
            $newW = $maxWidth;
            $newH = (int) round($origH * ($maxWidth / $origW));
        } else {
            $newW = $origW;
            $newH = $origH;
        }

        $dst = imagecreatetruecolor($newW, $newH);

        // Preservar transparencia para PNG
        if ($mimeType === 'image/png') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        ob_start();
        imagejpeg($dst, null, $quality);
        $output = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return $output;
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
        }
        catch (\Exception $e) {
            \Log::error('Error al eliminar archivo: ' . $e->getMessage());
            return false;
        }
    }
}