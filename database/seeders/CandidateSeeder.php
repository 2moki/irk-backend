<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CandidateDetail;
use App\Models\UserCertificate;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    public function run(): void
    {
        CandidateDetail::factory()->count(10)->create();
        UserCertificate::factory()->count(10)->create();
    }
}
