<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\StudyMode;
use Illuminate\Database\Seeder;

class StudyModeSeeder extends Seeder
{
    public function run(): void
    {
        collect(['Stacjonarne', 'Niestacjonarne', 'Online'])
            ->each(fn($name) => StudyMode::firstOrCreate(['name' => $name]));
    }
}
