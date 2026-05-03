<?php

declare(strict_types=1);

use App\Enums\ExamType;
use App\Models\GradeMapping;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table): void {
            $table->id();
            $table->decimal('min_value', 5, 2);
            $table->decimal('max_value', 5, 2);
            $table->decimal('conversion_rate', 5, 2)->default(1.00);
            $table->decimal('multiplier', 5, 2)->default(1.00);
            $table->boolean('is_bilingual')->default(false);
            $table->foreignIdFor(GradeMapping::class)->constrained()->cascadeOnDelete();
            $table->enum('exam_type', ExamType::cases());
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
