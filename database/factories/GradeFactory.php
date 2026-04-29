<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ExamType;
use App\Models\Grade;
use App\Models\GradeMapping;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Grade>
 */
class GradeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $min = $this->faker->numberBetween(0, 50);
        $max = $this->faker->numberBetween($min + 10, 100);

        return [
            'min_value' => $min,
            'max_value' => $max,
            'conversion_rate' => $this->faker->randomFloat(2, 0.5, 2.0),
            'multiplier' => $this->faker->randomElement([0.5, 0.7, 1.0, 1.5]),
            'is_bilingual' => $this->faker->boolean(20),
            'grade_mapping_id' => GradeMapping::factory(),
            'exam_type' => $this->faker->randomElement(ExamType::cases()),
        ];
    }
}
