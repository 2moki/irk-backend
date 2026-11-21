<?php

declare(strict_types=1);

namespace App\Filament\Resources\Candidates\Pages;

use App\Filament\Resources\Candidates\CandidatesResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCandidates extends CreateRecord
{
    protected static string $resource = CandidatesResource::class;
}
