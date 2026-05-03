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
            'user_certificate_id' => UserCertificate::factory(),
            'qualification_id' => Qualification::factory(),
            'value' => $this->faker->randomFloat(2, 0, 100),
            'is_bilingual' => $this->faker->boolean(15),
        ];
    }
}
