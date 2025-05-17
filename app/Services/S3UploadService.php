<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;

class S3UploadService
{
    protected $s3Client;
    protected $bucket;

    public function __construct()
    {
        $this->bucket = config('filesystems.disks.s3.bucket');
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region'  => config('filesystems.disks.s3.region'),
            'credentials' => [
                'key'    => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
        ]);
    }

    /**
     * Sube un archivo a S3 y retorna la URL
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
            
            // Crear la ruta completa
            $filePath = $folder . '/' . $fileName;
            
            // Subir el archivo a S3
            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'Key'    => $filePath,
                'Body'   => file_get_contents($file),
                'ACL'    => 'public-read',
                'ContentType' => $file->getMimeType(),
            ]);
            
            // Construir la URL
            $url = $result['ObjectURL'];
            
            return [
                'url' => $url,
                'path' => $filePath,
                'filename' => $fileName
            ];
        } catch (\Exception $e) {
            \Log::error('Error al subir archivo a S3: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Elimina un archivo de S3
     *
     * @param string $path
     * @return bool
     */
    public function deleteFile($path)
    {
        try {
            $this->s3Client->deleteObject([
                'Bucket' => $this->bucket,
                'Key'    => $path
            ]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Error al eliminar archivo de S3: ' . $e->getMessage());
            return false;
        }
    }
} 