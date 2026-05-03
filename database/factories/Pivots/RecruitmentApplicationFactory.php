<?php

declare(strict_types=1);

namespace Database\Factories\Pivots;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Pivots\RecruitmentApplication;
use App\Models\Recruitment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecruitmentApplication>
 */
class RecruitmentApplicationFactory extends Factory
{
    protected $model = RecruitmentApplication::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'recruitment_id' => Recruitment::inRandomOrder()->first()->id ?? Recruitment::factory(),
            'got_points' => $this->faker->randomFloat(2, 0, 100),
            'max_points' => 100.00,
            'priority' => $this->faker->numberBetween(1, 5),
            'is_paid' => $this->faker->boolean(),
            'payment_date' => $this->faker->date(),
            'application_status' => $this->faker->randomElement(ApplicationStatus::cases()),
        ];
    }
}
