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
        Schema::create('qualification_categories', function (Blueprint $table): void {
            $table->id();
            $table->string("name");
            $table->timestamps();
        });

        $categories = [
            ['name' => "Przedmiot ze szkoły średniej"],
            ['name' => "Przedmiot ze starej matury"],
            ['name' => 'Przedmiot z egzaminu maturalnego (podstawa)'],
            ['name' => 'Przedmiot z egzaminu maturalnego (rozszerzenie)'],
            ['name' => 'Egzamin zawodowy'],
            ['name' => 'Egzamin kwalifikacyjny'],
        ];

        DB::table('qualification_categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualification_categories');
    }
};
