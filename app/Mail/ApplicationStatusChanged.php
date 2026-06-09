<?php

declare(strict_types=1);

namespace App\Mail;

use App\Enums\ApplicationStatus;
use App\Models\Pivots\RecruitmentApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusChanged extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private readonly RecruitmentApplication $recruitmentApplication,
        private readonly ApplicationStatus $previousStatus,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('mail.application_status_changed.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $application = $this->recruitmentApplication;
        $user = $application->application->user;
        $recruitment = $application->recruitment;

        return new Content(
            markdown: 'mail.application.application-status-changed',
            with: [
                'candidateName' => $user->first_name,
                'majorName' => $recruitment->major->detailed_name,
                'academicYear' => $recruitment->academicYear->start_year,
                'previousStatus' => $this->previousStatus->getLabel(),
                'newStatus' => $application->application_status->getLabel(),
                'detailsUrl' => config('app.frontend_url', config('app.url')),
            ],
        );
    }
}
