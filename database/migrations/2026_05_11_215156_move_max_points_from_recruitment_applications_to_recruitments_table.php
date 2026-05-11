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
        Schema::table('recruitments', function (Blueprint $table): void {
            $table->decimal('max_points')->default(0.00)->after('slots');
        });

        Schema::table('recruitment_application', function (Blueprint $table): void {
            $table->dropColumn('max_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recruitment_application', function (Blueprint $table): void {
            $table->decimal('max_points')->default(0.00)->after('got_points');
        });

        Schema::table('recruitments', function (Blueprint $table): void {
            $table->dropColumn('max_points');
        });
    }
};
