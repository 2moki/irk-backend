<?php

declare(strict_types=1);

use App\Models\Recruitment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('requirement_groups', function (Blueprint $table): void {
            $table->id();
            $table->decimal('weight', 5, 2);
            $table->unsignedTinyInteger('qualifications_count');
            $table->foreignIdFor(Recruitment::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requirement_groups');
    }
};
