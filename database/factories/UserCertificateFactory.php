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
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'exam_type' => $this->faker->randomElement(ExamType::cases()),
            'school_id' => School::inRandomOrder()->first()->id ?? School::factory(),
            'school_custom_name' => null,
            'issue_date' => $this->faker->date(),
            'is_annex' => false,
            'document_number' => $this->faker->unique()->numerify('MM/####/####'),
            'is_verified' => false,
            'document_id' => null,
        ];
    }
}
