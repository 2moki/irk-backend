<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BillingType;
use App\Models\AcademicYear;
use App\Models\GradeMapping;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        $gradeMapping = GradeMapping::where('name', 'Skala procentowa (nowa matura)')->first();

        if (! $gradeMapping) {
            return;
        }

        $years = [
            ['start_year' => 2025, 'billing_type' => BillingType::EACH_GROUP_SEPARATELY],
            ['start_year' => 2026, 'billing_type' => BillingType::EACH_MAJOR_SEPARATELY],
            ['start_year' => 2027, 'billing_type' => BillingType::HIGHEST_FEE_ONLY],
        ];

        foreach ($years as $year) {
            AcademicYear::firstOrCreate(
                ['start_year' => $year['start_year']],
                [
                    'billing_type' => $year['billing_type'],
                    'grade_mapping_id' => $gradeMapping->id,
                ],
            );
        }
    }
}
