<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\QualificationCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QualificationCategory>
 */
class QualificationCategoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Przedmiot ze szkoły średniej',
                'Przedmiot ze starej matury',
                'Przedmiot z egzaminu maturalnego (podstawa)',
                'Przedmiot z egzaminu maturalnego (rozszerzenie)',
                'Egzamin zawodowy',
                'Egzamin kwalifikacyjny',
            ]),
        ];
    }
}
