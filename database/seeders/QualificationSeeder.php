<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\QualificationCategoryEnum;
use App\Models\Qualification;
use Illuminate\Database\Seeder;

class QualificationSeeder extends Seeder
{
    public function run(): void
    {
        // Qualification::factory()->count(12)->create();

        $exams = [
            'Język polski',
            'Matematyka',
            'Język angielski',
            'Język francuski',
            'Język hiszpański',
            'Język niemiecki',
            'Język rosyjski',
            'Język włoski',
        ];
        foreach ($exams as $exam) {
            $qualification = Qualification::create([
                'name' => $exam,
                'qualification_category_id' => QualificationCategoryEnum::MATURA_GRADE->id(),
            ]);
        }
    }
}
