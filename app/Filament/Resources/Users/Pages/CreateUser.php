<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Pages;

use App\Enums\Auth\RoleType;
use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $this->record->assignRole(RoleType::EMPLOYEE->value);
    }
}
