<?php

declare(strict_types=1);

use App\Enums\DisabilityLevel;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('candidate_details', function (Blueprint $table): void {
            $table->id();
            $table->string('nationality', 100);
            $table->boolean('has_disability')->default(false);
            $table->enum('disability_level', DisabilityLevel::cases())->nullable();
            $table->foreignIdFor(UserDocument::class, 'photo_document_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(UserDocument::class, 'identity_document_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class)->unique()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_details');
    }
};
