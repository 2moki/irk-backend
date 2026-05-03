<?php

declare(strict_types=1);

use App\Enums\SchoolType;
use App\Models\Voivodeship;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table): void {
            $table->id();
            $table->string('rspo_id', 20)->nullable()->unique();
            $table->string('name')->index();
            $table->string('city')->nullable()->index();
            $table->foreignIdFor(Voivodeship::class)->nullable()->constrained()->nullOnDelete();
            $table->enum('school_type', SchoolType::cases());
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
