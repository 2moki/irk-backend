<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ExamType;
use App\Models\Grade;
use App\Models\GradeMapping;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $newMaturaMapping = GradeMapping::where('name', 'Skala procentowa (nowa matura)')->first();

        if ($newMaturaMapping) {
            $ranges = [
                ['min' => 0, 'max' => 29, 'rate' => 0.00, 'multiplier' => 1.00],
                ['min' => 30, 'max' => 49, 'rate' => 0.30, 'multiplier' => 1.00],
                ['min' => 50, 'max' => 69, 'rate' => 0.50, 'multiplier' => 1.00],
                ['min' => 70, 'max' => 89, 'rate' => 0.70, 'multiplier' => 1.00],
                ['min' => 90, 'max' => 100, 'rate' => 1.00, 'multiplier' => 1.00],
            ];

            foreach ($ranges as $range) {
                foreach ([false, true] as $bilingual) {
                    Grade::firstOrCreate([
                        'grade_mapping_id' => $newMaturaMapping->id,
                        'exam_type' => ExamType::NEW_MATURA,
                        'min_value' => $range['min'],
                        'max_value' => $range['max'],
                        'is_bilingual' => $bilingual,
                    ], [
                        'conversion_rate' => $range['rate'],
                        'multiplier' => $bilingual ? 1.50 : $range['multiplier'],
                    ]);
                }
            }
        }
    }
}
