<?php

declare(strict_types=1);

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Recruitment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('recruitment_application', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Application::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Recruitment::class)->constrained()->cascadeOnDelete();
            $table->decimal('got_points', 8, 2)->default(0.00);
            $table->decimal('max_points', 8, 2)->default(0.00);
            $table->unsignedTinyInteger('priority')->default(1);
            $table->boolean('is_paid')->default(false);
            $table->date('payment_date')->nullable();
            $table->enum('application_status', ApplicationStatus::cases())->default('pending');
            $table->timestamps();

            $table->unique(['application_id', 'recruitment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_application');
    }
};
