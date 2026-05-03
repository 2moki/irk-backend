<?php

declare(strict_types=1);

namespace App\Filament\Resources\Recruitments\Pages;

use App\Filament\Resources\Recruitments\RecruitmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRecruitment extends CreateRecord
{
    protected static string $resource = RecruitmentResource::class;

    protected function getRedirectUrl(): string
    {
        return RecruitmentResource::getUrl('edit', ['record' => $this->record]);
    }
}
