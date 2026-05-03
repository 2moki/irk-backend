<?php

declare(strict_types=1);

namespace App\Filament\Resources\Recruitments\Pages;

use App\Filament\Actions\SendRecruitmentEmailsAction;
use App\Filament\Resources\Recruitments\RecruitmentResource;
use App\Models\Recruitment;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewRecruitment extends ViewRecord
{
    protected static string $resource = RecruitmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            ActionGroup::make([
                SendRecruitmentEmailsAction::make(),
                Action::make('resume')
                    ->label(__('recruitments.resume'))
                    ->icon(Heroicon::OutlinedPlayCircle)
                    ->hidden(fn(Recruitment $recruitment): bool => ! $recruitment->is_suspended || $recruitment->end_date < now())
                    ->action(fn(Recruitment $recruitment): bool => $recruitment->update(['is_suspended' => false])),
                Action::make('suspend')
                    ->label(__('recruitments.suspend'))
                    ->icon(Heroicon::OutlinedPauseCircle)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalDescription(trans_choice('recruitments.suspend_confirmation', 1))
                    ->hidden(fn(Recruitment $recruitment): bool => $recruitment->is_suspended || $recruitment->end_date < now())
                    ->action(fn(Recruitment $recruitment): bool => $recruitment->update(['is_suspended' => true])),
            ]),
        ];
    }
}
