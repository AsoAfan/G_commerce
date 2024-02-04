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
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph(7),
            'quantity' => $this->faker->randomNumber(),
            'price' => $this->faker->randomFloat(2, 1),
            'currency' => $this->faker->currencyCode,
            'image_path' => $this->faker->filePath(),
            'image_name' => $this->faker->domainName,

        ];
    }
}
