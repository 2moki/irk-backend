<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ExamType;
use App\Models\School;
use App\Models\User;
use App\Models\UserCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserCertificate>
 */
class UserCertificateFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'exam_type' => $this->faker->randomElement(ExamType::cases()),
            'school_id' => School::factory(),
            'school_custom_name' => null,
            'issue_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'is_annex' => $this->faker->boolean(10),
            'document_number' => $this->faker->unique()->regexify('[A-Z]{2}/[0-9]{4}/[0-9]{4}'),
            'is_verified' => $this->faker->boolean(40),
            'document_id' => null,
        ];
    }
}
