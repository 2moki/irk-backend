<?php

declare(strict_types=1);

namespace Database\Factories;

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
        return [
            'nationality' => $this->faker->randomElement(['polska', 'ukraińska', 'białoruska', 'niemiecka']),
            'has_disability' => false,
            'disability_level' => null,
            'photo_document_id' => null,
            'identity_document_id' => null,
            'user_id' => User::factory(),
        ];
    }
}
