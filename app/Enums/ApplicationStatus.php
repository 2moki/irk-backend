<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ApplicationStatus: string implements HasLabel
{
    case PENDING = 'pending';
    case QUALIFIED = 'qualified';
    case RESERVE = 'reserve';
    case UNQUALIFIED = 'unqualified';

    public function getLabel(): string
    {
        return __('application_statuses.' . $this->value);
    }
}
