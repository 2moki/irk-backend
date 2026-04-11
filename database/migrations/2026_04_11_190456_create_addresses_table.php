<?php

declare(strict_types=1);

use App\Models\Country;
use App\Models\Voivodeship;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table): void {
            $table->id();

            $table->foreignIdFor(Country::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Voivodeship::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('state')->nullable();
            $table->string('post_code', 10);
            $table->string('city');
            $table->string('street');
            $table->string('house_number', 10);
            $table->string('apartment_number', 10)->nullable();
            $table->string('post_office');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
