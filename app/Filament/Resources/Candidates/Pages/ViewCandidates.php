<?php

declare(strict_types=1);

namespace App\Filament\Resources\Candidates\Pages;

use App\Filament\Resources\Candidates\CandidatesResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCandidates extends ViewRecord
{
    protected static string $resource = CandidatesResource::class;

    public function getTitle(): string
    {
        return 'Podgląd kandydata';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
