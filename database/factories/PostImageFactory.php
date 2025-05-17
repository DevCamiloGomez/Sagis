<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'asset_url' => 'https://placehold.co/640x480?text=Post+Image'
        ];
    }
}
