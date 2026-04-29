<?php

declare(strict_types=1);

use App\Enums\BillingType;
use App\Models\GradeMapping;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('start_year')->unique();
            $table->enum('billing_type', BillingType::cases());
            $table->foreignIdFor(GradeMapping::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
