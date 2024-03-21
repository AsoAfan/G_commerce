<?php

namespace Database\Factories;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Discount>
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
            'ratio' => $this->faker->numberBetween(1, 100),
            'starts_at' => $starts_at,
            'expires_at' => $expires_at
        ];
    }
}
