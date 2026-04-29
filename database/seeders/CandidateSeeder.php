<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ExamType;
use App\Models\Application;
use App\Models\CandidateDetail;
use App\Models\Recruitment;
use App\Models\RecruitmentApplication;
use App\Models\User;
use App\Models\UserCertificate;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    public function run(): void
    {
        $recruitments = Recruitment::all();

        if ($recruitments->isEmpty()) {
            return;
        }

        $candidates = User::role('candidate')->get();

        foreach ($candidates->take(15) as $candidate) {
            CandidateDetail::factory()->create([
                'user_id' => $candidate->id,
            ]);

            UserCertificate::factory()->create([
                'user_id' => $candidate->id,
                'exam_type' => ExamType::NEW_MATURA,
            ]);

            $application = Application::factory()->create([
                'user_id' => $candidate->id,
                'exam_type' => ExamType::NEW_MATURA,
            ]);

            $selectedRecruitments = $recruitments->random(min(3, $recruitments->count()));

            foreach ($selectedRecruitments as $priority => $recruitment) {
                RecruitmentApplication::factory()->create([
                    'application_id' => $application->id,
                    'recruitment_id' => $recruitment->id,
                    'priority' => $priority + 1,
                ]);
            }
        }
    }
}
