<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => null,
            'name' => fake()->word().' product',
            'brand' => 'Milwaukee',
            'sku' => fake()->uuid(),
            'price' => fake()->randomFloat(2,25, 105),
            'is_available' => (bool)mt_rand(0,1)
        ];
    }
}
