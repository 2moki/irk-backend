<?php

declare(strict_types=1);

use App\Enums\ExamType;
use App\Models\School;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('user_certificates', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->enum('exam_type', ExamType::cases());
            $table->foreignIdFor(School::class)->nullable()->constrained()->nullOnDelete();
            $table->string('school_custom_name')->nullable();
            $table->date('issue_date');
            $table->boolean('is_annex')->default(false);
            $table->string('document_number', 50)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->foreignIdFor(UserDocument::class, 'document_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_certificates');
    }
};
