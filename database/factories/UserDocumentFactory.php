<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserDocument>
 */
class UserDocumentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $documentType = $this->faker->randomElement(DocumentType::cases());

        return [
            'user_id' => User::factory(),
            'document_type' => $documentType,
            'file_path' => "documents/{$this->faker->uuid()}.pdf",
            'file_name' => "{$documentType->value}_{$this->faker->uuid()}.pdf",
            'document_status' => $this->faker->randomElement(DocumentStatus::cases()),
        ];
    }
}
