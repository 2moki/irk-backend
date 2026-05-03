<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SchoolType;
use App\Models\School;
use App\Models\Voivodeship;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<School>
 */
class SchoolFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rspo_id' => $this->faker->unique()->numerify('########'),
            'name' => "Technikum nr {$this->faker->numberBetween(1, 50)} w {$this->faker->city()}",
            'city' => $this->faker->city(),
            'voivodeship_id' => Voivodeship::all()->random()->id,
            'school_type' => $this->faker->randomElement(SchoolType::cases()),
        ];
    }
}
