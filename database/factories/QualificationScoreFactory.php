<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Qualification;
use App\Models\QualificationScore;
use App\Models\UserCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QualificationScore>
 */
class QualificationScoreFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_certificate_id' => UserCertificate::inRandomOrder()->first()->id ?? UserCertificate::factory(),
            'qualification_id' => Qualification::inRandomOrder()->first()->id ?? Qualification::factory(),
            'value' => $this->faker->numberBetween(0, 100),
            'is_bilingual' => false,
        ];
    }
}
