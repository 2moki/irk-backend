<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\QualificationCategoryEnum;
use App\Models\QualificationCategory;
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
                'name' => $type->rawString(),
            ]);
        }
    }
}
