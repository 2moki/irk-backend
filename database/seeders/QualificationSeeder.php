<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Qualification;
use Illuminate\Database\Seeder;

class QualificationSeeder extends Seeder
{
    public function run(): void
    {
        Qualification::factory()->count(12)->create();
    }
}
