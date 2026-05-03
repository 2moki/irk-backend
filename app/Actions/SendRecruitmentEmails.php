<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\ApplicationStatus;
use App\Mail\SendInfo;
use App\Models\Recruitment;
use Illuminate\Support\Facades\Mail;

class SendRecruitmentEmails
{
    /**
     * @param array{
     *     subject: string,
     *     statuses: array<ApplicationStatus>,
     *     body: string
     * } $data
     */
    public function execute(Recruitment $recruitment, array $data): void
    {
        foreach ($recruitment->applications as $application) {
            if (! empty($data['statuses']) && ! in_array(ApplicationStatus::from($application->pivot->application_status), $data['statuses'])) {
                continue;
            }

            Mail::to($application->user)
                ->queue(
                    new SendInfo(
                        mailSubject: $data['subject'],
                        mailBody: $data['body'],
                    ),
                );
        }
    }
}
