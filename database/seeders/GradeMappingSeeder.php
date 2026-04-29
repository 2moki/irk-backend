<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\GradeMapping;
use Illuminate\Database\Seeder;

class GradeMappingSeeder extends Seeder
{
    public function run(): void
    {
        $mappings = [
            'Skala procentowa (nowa matura)',
            'Skala 2-5 (stara matura)',
            'Skala IB (1-7)',
            'Skala EB (1-10)',
        ];

        collect($mappings)->each(
            fn(string $name) => GradeMapping::firstOrCreate(['name' => $name]),
        );
    }
}
