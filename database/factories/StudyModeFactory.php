<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\StudyMode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudyMode>
 */
class StudyModeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Stacjonarne',
                'Niestacjonarne',
                'Online',
            ]),
        ];
    }
}
