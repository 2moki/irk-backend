<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DegreeTitle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DegreeTitle>
 */
class DegreeTitleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'lic.',
                'inż.',
                'mgr',
                'dr',
            ]),
        ];
    }
}
