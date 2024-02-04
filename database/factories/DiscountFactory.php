<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $starts_at = $this->faker->dateTime();
        $expires_at = $this->faker->dateTimeBetween($starts_at, now()->addDays(rand(1, 10)));
        return [
            'ratio' => $this->faker->randomFloat(2, 0.01, 0.9),
            'starts_at' => $starts_at,
            'expires_at' => $expires_at
        ];
    }
}
