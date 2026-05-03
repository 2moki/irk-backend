<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Recruitment;
use App\Models\RequirementGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RequirementGroup>
 */
class RequirementGroupFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Grupa ' . $this->faker->numberBetween(1, 10),
            'weight' => $this->faker->randomFloat(2, 0.1, 1.0),
            'qualifications_count' => $this->faker->numberBetween(1, 5),
            'recruitment_id' => Recruitment::inRandomOrder()->first()->id ?? Recruitment::factory(),
        ];
    }
}
