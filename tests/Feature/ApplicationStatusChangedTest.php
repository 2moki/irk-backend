<?php

declare(strict_types=1);

use App\Enums\ApplicationStatus;
use App\Mail\ApplicationStatusChanged;
use App\Models\Pivots\RecruitmentApplication;
use Illuminate\Support\Facades\Mail;

it('wysyła maila do kandydata gdy status aplikacji się zmienia', function (): void {
    Mail::fake();

    $recruitmentApplication = RecruitmentApplication::factory()->create([
        'application_status' => ApplicationStatus::PENDING,
    ]);

    $recruitmentApplication->update([
        'application_status' => ApplicationStatus::QUALIFIED,
    ]);

    Mail::assertQueued(
        ApplicationStatusChanged::class,
        fn (ApplicationStatusChanged $mail): bool => $mail->hasTo(
            $recruitmentApplication->application->user->email,
        ),
    );
});

it('nie wysyła maila gdy zmienia się inne pole niż application_status', function (): void {
    Mail::fake();

    $recruitmentApplication = RecruitmentApplication::factory()->create([
        'is_paid' => false,
    ]);

    $recruitmentApplication->update([
        'is_paid' => true,
    ]);

    Mail::assertNothingQueued();
});

it('wysyła maila przy każdej zmianie statusu niezależnie od wartości', function (): void {
    Mail::fake();

    $recruitmentApplication = RecruitmentApplication::factory()->create([
        'application_status' => ApplicationStatus::PENDING,
    ]);

    foreach ([ApplicationStatus::QUALIFIED, ApplicationStatus::RESERVE, ApplicationStatus::UNQUALIFIED] as $status) {
        $recruitmentApplication->update(['application_status' => $status]);
    }

    Mail::assertQueuedCount(3, ApplicationStatusChanged::class);
});
