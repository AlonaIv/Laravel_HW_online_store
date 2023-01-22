<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->words(rand(1, 2), true),
            'description' => $this->faker->paragraph(rand(1, 5)),
            'SKU' => $this->faker->unique()->ean8(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'discount' => rand(0, 90),
            'quantity' => rand(0, 15),
            'thumbnail' => $this->faker->imageUrl(category: 'cars', randomize: true),
        ];
    }
}
