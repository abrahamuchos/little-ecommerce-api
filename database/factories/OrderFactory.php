<?php

namespace Database\Factories;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $completeStatus = Attribute::where('name', '=',  'Complete')->first('id');

        return [
            'user_id' => null,
            'total' => null,
            'charger_id' => fake()->uuid,
            'status' => $completeStatus
        ];
    }
}
