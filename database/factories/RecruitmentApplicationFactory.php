<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Recruitment;
use App\Models\RecruitmentApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecruitmentApplication>
 */
class RecruitmentApplicationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $maxPoints = $this->faker->randomFloat(2, 50, 200);
        $isPaid = $this->faker->boolean(60);

        return [
            'application_id' => Application::factory(),
            'recruitment_id' => Recruitment::factory(),
            'got_points' => $this->faker->randomFloat(2, 0, $maxPoints),
            'max_points' => $maxPoints,
            'priority' => $this->faker->numberBetween(1, 5),
            'is_paid' => $isPaid,
            'payment_date' => $isPaid ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
            'application_status' => $this->faker->randomElement(ApplicationStatus::cases()),
        ];
    }
}
