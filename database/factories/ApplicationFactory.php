<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ExamType;
use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'money_balance' => $this->faker->randomFloat(2, 0, 500),
            'required_balance' => $this->faker->randomElement([85.00, 100.00, 150.00]),
            'documents_delivered' => $this->faker->boolean(30),
            'exam_type' => $this->faker->randomElement(ExamType::cases()),
        ];
    }
}
