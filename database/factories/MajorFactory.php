<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DegreeTitle;
use App\Models\Major;
use App\Models\StudyLevel;
use App\Models\StudyMode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Major>
 */
class MajorFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Informatyka',
                'Zarządzanie',
                'Ekonomia',
                'Matematyka',
                'Automatyka i robotyka',
                'Prawo',
                'Medycyna',
                'Filologia angielska',
                'Psychologia',
                'Architektura',
            ]),
            'semesters' => $this->faker->randomElement([6, 7, 8, 10]),
            'study_level_id' => StudyLevel::inRandomOrder()->first()->id ?? StudyLevel::factory(),
            'study_mode_id' => StudyMode::inRandomOrder()->first()->id ?? StudyMode::factory(),
            'degree_title_id' => DegreeTitle::inRandomOrder()->first()->id ?? DegreeTitle::factory(),
        ];
    }
}
