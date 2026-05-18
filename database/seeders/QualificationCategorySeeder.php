<?php

namespace Database\Seeders;

use App\Enums\ExamType;
use App\Enums\QualificationCategoryEnum;
use App\Models\QualificationCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QualificationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (QualificationCategoryEnum::cases() as $type) {
            $qcat = QualificationCategory::create([
                'id' => $type->id(),
                'name' => $type->rawString()
            ]);
        }
    }
}
