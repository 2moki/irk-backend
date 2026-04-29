<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['name' => 'Polski', 'code' => 'pl'],
            ['name' => 'Angielski', 'code' => 'en'],
            ['name' => 'Niemiecki', 'code' => 'de'],
            ['name' => 'Francuski', 'code' => 'fr'],
            ['name' => 'Hiszpański', 'code' => 'es'],
            ['name' => 'Rosyjski', 'code' => 'ru'],
            ['name' => 'Włoski', 'code' => 'it'],
        ];

        collect($languages)->each(
            fn(array $lang) => Language::firstOrCreate(['code' => $lang['code']], $lang),
        );
    }
}
