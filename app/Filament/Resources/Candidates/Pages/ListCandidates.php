<?php

declare(strict_types=1);

namespace App\Filament\Resources\Candidates\Pages;

use App\Filament\Resources\Candidates\CandidatesResource;
use Filament\Resources\Pages\ListRecords;

class ListCandidates extends ListRecords
{
    protected static string $resource = CandidatesResource::class;

    // Brak metody getHeaderActions() oznacza brak przycisku "Stwórz"
}
