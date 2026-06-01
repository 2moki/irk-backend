<?php

namespace Database\Factories\Pivots;

use App\Models\Model;
use App\Models\Qualification;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\RequirementGroup;

/**
 * @extends Factory<Model>
 */
class RequirementGroupQualificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'requirement_group_id' => RequirementGroup::inRandomOrder()->first()?->id ?? RequirementGroupFactory::new(),
            'qualification_id' => Qualification::inRandomOrder()->first()?->id ?? QualificationFactory::new(),
            'weight' => $this->faker->numberBetween(1,4)
        ];
    }
}
