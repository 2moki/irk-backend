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
            'name' => $this->faker->unique()->randomElement([
                'Skala procentowa (nowa matura)',
                'Skala 2-5 (stara matura)',
                'Skala IB (1-7)',
                'Skala EB (1-10)',
            ]),
        ];
    }
}
