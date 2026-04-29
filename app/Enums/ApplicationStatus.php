<?php

declare(strict_types=1);

namespace App\Enums;

enum ApplicationStatus: string
{
    case PENDING = 'pending';
    case QUALIFIED = 'qualified';
    case RESERVE = 'reserve';
    case UNQUALIFIED = 'unqualified';

    public function label(): string
    {
        return __('application_statuses.' . $this->value);
    }
}
