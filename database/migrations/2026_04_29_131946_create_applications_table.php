<?php

declare(strict_types=1);

use App\Enums\ExamType;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->decimal('money_balance', 10, 2)->default(0.00);
            $table->decimal('required_balance', 10, 2)->default(0.00);
            $table->boolean('documents_delivered')->default(false);
            $table->enum('exam_type', ExamType::cases());
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
