<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\ApplicationStatus;
use App\Mail\ApplicationStatusChanged;
use App\Models\Pivots\RecruitmentApplication;
use Illuminate\Support\Facades\Mail;

class RecruitmentApplicationObserver
{
    /**
     * Obsługuje zdarzenie aktualizacji zgłoszenia rekrutacyjnego.
     * Wysyła powiadomienie e-mail do kandydata, gdy zmieni się status aplikacji.
     */
    public function updated(RecruitmentApplication $recruitmentApplication): void
    {
        if (! $recruitmentApplication->wasChanged('application_status')) {
            return;
        }

        // getOriginal() zwraca już skasetowaną wartość enum (dzięki castowi w modelu),
        // dlatego wystarczy rzutowanie — nie potrzeba ::from().
        /** @var ApplicationStatus $previousStatus */
        $previousStatus = $recruitmentApplication->getOriginal('application_status');

        if (! $previousStatus instanceof ApplicationStatus) {
            $previousStatus = ApplicationStatus::from($previousStatus);
        }

        $recruitmentApplication->loadMissing([
            'application.user',
            'recruitment.major',
            'recruitment.academicYear',
        ]);

        Mail::to($recruitmentApplication->application->user)
            ->queue(
                new ApplicationStatusChanged(
                    recruitmentApplication: $recruitmentApplication,
                    previousStatus: $previousStatus,
                ),
            );
    }
}
