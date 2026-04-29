<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Cost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cost>
 */
class CostFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => $this->faker->randomElement([85.00, 100.00, 150.00, 200.00]),
        ];
    }
}
