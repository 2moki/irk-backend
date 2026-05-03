<?php

declare(strict_types=1);

namespace App\Filament\Resources\Qualifications\Pages;

use App\Filament\Resources\Qualifications\QualificationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQualification extends CreateRecord
{
    protected static string $resource = QualificationResource::class;
}
