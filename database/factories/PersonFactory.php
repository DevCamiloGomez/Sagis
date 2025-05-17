<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'lastname' => $this->faker->lastName,
            'document' => '1090' . $this->faker->unique()->randomNumber(4, true),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'telephone' => $this->faker->e164PhoneNumber,
            'email' => $this->faker->unique()->email,
            'birthdate' => $this->faker->date('Y-m-d', '-18 years'),
            'image_url' => 'https://placehold.co/640x480?text=Profile+Image'
        ];
    }
}
