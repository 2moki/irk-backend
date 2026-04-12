<?php

declare(strict_types=1);

namespace App\Filament\Resources\Majors\Pages;

use App\Filament\Resources\Majors\MajorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMajor extends CreateRecord
{
    protected static string $resource = MajorResource::class;
}
