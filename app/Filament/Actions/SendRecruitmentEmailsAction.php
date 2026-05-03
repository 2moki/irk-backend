<?php

declare(strict_types=1);

namespace App\Filament\Actions;

use App\Actions\SendRecruitmentEmails;
use App\Enums\ApplicationStatus;
use App\Models\Recruitment;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class SendRecruitmentEmailsAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('recruitments.send_email'))
            ->icon(Heroicon::OutlinedEnvelope)
            ->schema([
                TextInput::make('subject')
                    ->label(Str::ucfirst(__('validation.attributes.subject')))
                    ->required()
                    ->minLength(3)
                    ->maxLength(80),
                Select::make('statuses')
                    ->label(trans_choice('Application status', 2))
                    ->options(ApplicationStatus::class)
                    ->placeholder(__('Everyone'))
                    ->multiple(),
                RichEditor::make('body')
                    ->label(Str::ucfirst(__('validation.attributes.body')))
                    ->fileAttachmentsDirectory('attachments')
                    ->resizableImages()
                    ->required()
                    ->minLength(10)
                    ->maxLength(2000),
            ])
            ->modalSubmitActionLabel(__('actions.send'))
            ->action(function (Recruitment $record, array $data): void {
                $record->loadMissing(['applications', 'applications.user']);

                app(SendRecruitmentEmails::class)->execute($record, $data);
            });
    }
    public static function getDefaultName(): ?string
    {
        return 'email';
    }
}
