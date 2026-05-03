<?php

declare(strict_types=1);

use App\Models\Language;
use App\Models\Pivots\RecruitmentApplication;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('application_language', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(RecruitmentApplication::class, 'recruitment_application_id')->constrained('recruitment_application')->cascadeOnDelete();
            $table->foreignIdFor(Language::class)->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('priority')->default(1);
            $table->timestamps();

            $table->unique(['recruitment_application_id', 'language_id'], 'app_lang_unique');
            $table->unique(['recruitment_application_id', 'priority'], 'app_priority_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_language');
    }
};
