<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DisabilityLevel;
use App\Models\CandidateDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CandidateDetail>
 */
class CandidateDetailFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hasDisability = $this->faker->boolean(15);

        return [
            'nationality' => $this->faker->randomElement(['polska', 'ukraińska', 'białoruska', 'niemiecka', 'inna']),
            'has_disability' => $hasDisability,
            'disability_level' => $hasDisability
                ? $this->faker->randomElement(DisabilityLevel::cases())
                : null,
            'photo_document_id' => null,
            'identity_document_id' => null,
            'user_id' => User::factory(),
        ];
    }
}
