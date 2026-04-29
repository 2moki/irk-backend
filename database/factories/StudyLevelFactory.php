<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\StudyLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudyLevel>
 */
class StudyLevelFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Licencjat',
                'Inżynier',
                'Magister',
                'Doktorat',
            ]),
        ];
    }
}
