<?php

declare(strict_types=1);

namespace App\Filament\Resources\Recruitments\Pages;

use App\Filament\Resources\Recruitments\RecruitmentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRecruitment extends ViewRecord
{
    protected static string $resource = RecruitmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
