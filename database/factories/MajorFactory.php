<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DegreeTitle;
use App\Models\Major;
use App\Models\StudyLevel;
use App\Models\StudyMode;
use Illuminate\Database\Eloquent\Factories\Factory;

class MajorFactory extends Factory
{
    protected $model = Major::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Informatyka',
                'Zarządzanie',
                'Ekonomia',
                'Matematyka',
                'Automatyka i robotyka',
            ]),
            'semesters' => $this->faker->randomElement([6, 7, 8, 10]),
            'study_level_id' => StudyLevel::inRandomOrder()->first()->id,
            'study_mode_id' => StudyMode::inRandomOrder()->first()->id,
            'degree_title_id' => DegreeTitle::inRandomOrder()->first()->id,
        ];
    }
}
