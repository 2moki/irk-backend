<?php

declare(strict_types=1);

namespace App\Filament\Resources\Languages\Pages;

use App\Filament\Resources\Languages\LanguageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageLanguages extends ManageRecords
{
    protected static string $resource = LanguageResource::class;

    public function getModelLabel(): ?string
    {
        return trans_choice('Foreign language', 1);
    }

    public function getPluralModelLabel(): ?string
    {
        return trans_choice('Foreign language', 2);
    }

    public function getTitle(): string|Htmlable
    {
        return trans_choice('Foreign language', 2);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
