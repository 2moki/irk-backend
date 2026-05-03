<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Recruitment;
use Illuminate\Database\Seeder;

class RecruitmentSeeder extends Seeder
{
    public function run(): void
    {
        Recruitment::factory()->count(10)->create();
    }
}
