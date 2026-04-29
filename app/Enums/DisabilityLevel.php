<?php

declare(strict_types=1);

namespace App\Enums;

enum DisabilityLevel: string
{
    case MILD = 'mild';
    case MODERATE = 'moderate';
    case SEVERE = 'severe';

    public function label(): string
    {
        return __('disability_levels.' . $this->value);
    }
}
