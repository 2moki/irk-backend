<?php

declare(strict_types=1);

namespace App\Filament\Resources\Recruitments\Pages;

use App\Filament\Resources\Recruitments\RecruitmentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRecruitment extends EditRecord
{
    protected static string $resource = RecruitmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
