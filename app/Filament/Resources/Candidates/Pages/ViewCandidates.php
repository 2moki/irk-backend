<?php

namespace App\Filament\Resources\Candidates\Pages;

use App\Filament\Resources\Candidates\CandidatesResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\EditAction;

class ViewCandidates extends ViewRecord
{
    protected static string $resource = CandidatesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Podgląd kandydata';
    }
}