<?php

declare(strict_types=1);

use App\Models\Qualification;
use App\Models\RequirementGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('group_major_qualification', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(RequirementGroup::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Qualification::class)->constrained()->cascadeOnDelete();
            $table->decimal('weight', 5, 2);
            $table->timestamps();

            $table->unique(['requirement_group_id', 'qualification_id'], 'gmq_group_qualification_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_major_qualification');
    }
};
