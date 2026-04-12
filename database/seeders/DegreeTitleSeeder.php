<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\DegreeTitle;
use Illuminate\Database\Seeder;

class DegreeTitleSeeder extends Seeder
{
    public function run(): void
    {
        collect(['lic.', 'inż.', 'mgr', 'dr'])
            ->each(fn($name) => DegreeTitle::firstOrCreate(['name' => $name]));
    }
}
