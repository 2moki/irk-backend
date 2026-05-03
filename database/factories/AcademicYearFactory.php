<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BillingType;
use App\Models\AcademicYear;
use App\Models\GradeMapping;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_year' => $this->faker->unique()->numberBetween(2020, 2035),
            'billing_type' => $this->faker->randomElement(BillingType::cases()),
            'grade_mapping_id' => GradeMapping::inRandomOrder()->first()->id ?? GradeMapping::factory(),
        ];
    }
}
