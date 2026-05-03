<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Cost;
use App\Models\Major;
use App\Models\Recruitment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recruitment>
 */
class RecruitmentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('+1 month', '+3 months');
        $endDate = $this->faker->dateTimeBetween($startDate, '+6 months');

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'slots' => $this->faker->numberBetween(30, 200),
            'major_id' => Major::factory(),
            'academic_year_id' => AcademicYear::inRandomOrder()->first()->id ?? AcademicYear::factory(),
            'cost_id' => Cost::inRandomOrder()->first()->id ?? Cost::factory(),
        ];
    }
}
