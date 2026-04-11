<?php

declare(strict_types=1);

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table): void {
            $table->id();

            $table->char('code', 2)->unique();
            $table->string('name_en', 80);
            $table->string('name_pl', 80);
        });

        $path = database_path('data/countries.json');

        if (! file_exists($path)) {
            throw new FileNotFoundException("Could not find countries file in {$path}.");
        }

        $countries = json_decode(file_get_contents($path), true);

        foreach ($countries as $country) {
            $code = $country['cca2'];
            $nameEn = $country['name']['common'];
            $namePl = $country['translations']['pol']['common'] ?? $nameEn;

            DB::table('countries')->insert([
                'code' => $code,
                'name_en' => $nameEn,
                'name_pl' => $namePl,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
