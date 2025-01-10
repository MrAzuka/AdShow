<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ads>
 */
class AdsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 5),
            'title' => fake()->text(20),
            'description' => fake()->text(),
            'category_id' => fake()->numberBetween(1, 5),
            'location' => fake()->city(),
            'price' => fake()->numberBetween(2, 6),
            'is_active' => fake()->boolean(70),
        ];
    }
}
