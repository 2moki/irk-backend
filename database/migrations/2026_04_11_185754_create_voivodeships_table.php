<?php

declare(strict_types=1);

use App\Models\Country;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('voivodeships', function (Blueprint $table): void {
            $table->id();

            $table->foreignIdFor(Country::class);
            $table->string('name_en', 20);
            $table->string('name_pl', 20);
        });

        $voivodeships = [
            ['name_pl' => 'Dolnośląskie',        'name_en' => 'Lower Silesian'],
            ['name_pl' => 'Kujawsko-pomorskie',  'name_en' => 'Kuyavian-Pomeranian'],
            ['name_pl' => 'Lubelskie',           'name_en' => 'Lublin'],
            ['name_pl' => 'Lubuskie',            'name_en' => 'Lubusz'],
            ['name_pl' => 'Łódzkie',             'name_en' => 'Łódź'],
            ['name_pl' => 'Małopolskie',         'name_en' => 'Lesser Poland'],
            ['name_pl' => 'Mazowieckie',         'name_en' => 'Masovian'],
            ['name_pl' => 'Opolskie',            'name_en' => 'Opole'],
            ['name_pl' => 'Podkarpackie',        'name_en' => 'Subcarpathian'],
            ['name_pl' => 'Podlaskie',           'name_en' => 'Podlaskie'],
            ['name_pl' => 'Pomorskie',           'name_en' => 'Pomeranian'],
            ['name_pl' => 'Śląskie',             'name_en' => 'Silesian'],
            ['name_pl' => 'Świętokrzyskie',      'name_en' => 'Świętokrzyskie'],
            ['name_pl' => 'Warmińsko-mazurskie', 'name_en' => 'Warmian-Masurian'],
            ['name_pl' => 'Wielkopolskie',       'name_en' => 'Greater Poland'],
            ['name_pl' => 'Zachodniopomorskie',  'name_en' => 'West Pomeranian'],
        ];

        $countryId = DB::table('countries')
            ->where('code', 'PL')
            ->value('id');

        foreach ($voivodeships as $voivodeship) {
            DB::table('voivodeships')->insert([
                'country_id' => $countryId,
                'name_pl' => $voivodeship['name_pl'],
                'name_en' => $voivodeship['name_en'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voivodeships');
    }
};
