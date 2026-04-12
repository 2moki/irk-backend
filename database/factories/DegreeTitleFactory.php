<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DegreeTitle;
use Illuminate\Database\Eloquent\Factories\Factory;

class DegreeTitleFactory extends Factory
{
    protected $model = DegreeTitle::class;

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
