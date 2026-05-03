<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    public function run(): void
    {
        Major::factory()->count(10)->create();
    }
}
