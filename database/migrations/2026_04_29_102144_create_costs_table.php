<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('costs', function (Blueprint $table): void {
            $table->id();
            $table->decimal('price', 10, 2);
        });

        DB::table('costs')->insert([
            ['price' => 85.00],
            ['price' => 100.00],
            ['price' => 150.00],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('costs');
    }
};
