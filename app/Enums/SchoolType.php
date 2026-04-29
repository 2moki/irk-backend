<?php

declare(strict_types=1);

namespace App\Enums;

enum SchoolType: string
{
    case HIGH_SCHOOL = 'high_school';
    case TECHNICAL = 'technical';
    case VOCATIONAL = 'vocational';
    case FOREIGN = 'foreign';

    public function label(): string
    {
        return __('school_types.' . $this->value);
    }
}
