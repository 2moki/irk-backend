<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Qualification;
use App\Models\QualificationCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Qualification>
 */
class QualificationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Matematyka', 'Fizyka', 'Chemia', 'Biologia', 'Informatyka',
                'Język polski', 'Język angielski', 'Język niemiecki', 'Język francuski',
                'Historia', 'Geografia', 'WOS', 'Filozofia',
            ]),
            'qualification_category_id' => QualificationCategory::factory(),
        ];
    }
}
