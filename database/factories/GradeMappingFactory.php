<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\GradeMapping;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GradeMapping>
 */
class GradeMappingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word() . ' grading scale',
        ];
    }
}
