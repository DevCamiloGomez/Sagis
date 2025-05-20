<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarouselImage;

class CarouselImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Imágenes para el carrusel principal
        CarouselImage::create([
            'type' => 'main',
            'title' => 'Campus UFPS',
            'asset_url' => 'https://www.ufps.edu.co/images/ufps1.png',
            'order' => 0,
            'is_active' => true
        ]);

        CarouselImage::create([
            'type' => 'main',
            'title' => 'Instalaciones UFPS',
            'asset_url' => 'https://www.ufps.edu.co/images/ufps2.png',
            'order' => 1,
            'is_active' => true
        ]);

        CarouselImage::create([
            'type' => 'main',
            'title' => 'Comunidad UFPS',
            'asset_url' => 'https://www.ufps.edu.co/images/ufps3.png',
            'order' => 2,
            'is_active' => true
        ]);

        // Imágenes para el carrusel de sección
        CarouselImage::create([
            'type' => 'section',
            'title' => 'Biblioteca UFPS',
            'asset_url' => 'https://www.ufps.edu.co/images/biblioteca.jpg',
            'order' => 0,
            'is_active' => true
        ]);

        CarouselImage::create([
            'type' => 'section',
            'title' => 'Laboratorios UFPS',
            'asset_url' => 'https://www.ufps.edu.co/images/laboratorios.jpg',
            'order' => 1,
            'is_active' => true
        ]);

        CarouselImage::create([
            'type' => 'section',
            'title' => 'Deportes UFPS',
            'asset_url' => 'https://www.ufps.edu.co/images/deportes.jpg',
            'order' => 2,
            'is_active' => true
        ]);
    }
}
