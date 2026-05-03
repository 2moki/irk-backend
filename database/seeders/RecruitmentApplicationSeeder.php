<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\RecruitmentApplication;
use Illuminate\Database\Seeder;

class RecruitmentApplicationSeeder extends Seeder
{
    public function run(): void
    {
        RecruitmentApplication::factory()
            ->count(10)
            ->create();
    }
}
