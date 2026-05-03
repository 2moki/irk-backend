<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\GradeMapping;
use Illuminate\Database\Seeder;

class GradeMappingSeeder extends Seeder
{
    public function run(): void
    {
        GradeMapping::factory()->count(4)->create();
    }
}
