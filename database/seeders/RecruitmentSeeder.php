<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Cost;
use App\Models\Language;
use App\Models\Major;
use App\Models\Recruitment;
use Illuminate\Database\Seeder;

class RecruitmentSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::where('start_year', 2026)->first();
        $cost = Cost::first();
        $majors = Major::all();
        $languages = Language::all();

        if (! $academicYear || ! $cost || $majors->isEmpty()) {
            return;
        }

        foreach ($majors->take(10) as $major) {
            Recruitment::factory()->create([
                'major_id' => $major->id,
                'academic_year_id' => $academicYear->id,
                'cost_id' => $cost->id,
                'start_date' => '2026-06-01',
                'end_date' => '2026-09-30',
            ]);

            if ($languages->isNotEmpty()) {
                $major->languages()->syncWithoutDetaching(
                    $languages->random(min(3, $languages->count()))->pluck('id'),
                );
            }
        }
    }
}
