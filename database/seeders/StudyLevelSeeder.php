<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\StudyLevel;
use Illuminate\Database\Seeder;

class StudyLevelSeeder extends Seeder
{
    public function run(): void
    {
        collect(['Licencjat', 'Inżynier', 'Magister', 'Doktorat'])
            ->each(fn($name) => StudyLevel::firstOrCreate(['name' => $name]));
    }
}
