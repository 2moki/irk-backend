<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('requirement_groups', function (Blueprint $table): void {
            $table->string('name', 30);

            $table->unique(['recruitment_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requirement_groups', function (Blueprint $table): void {
            $table->dropColumn('name');
        });
    }
};
