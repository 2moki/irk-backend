<?php

declare(strict_types=1);

namespace App\Enums;

enum RecruitmentStatus: string
{
    case WAITING = 'waiting';
    case ONGOING = 'ongoing';
    case FINISHED = 'finished';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return __('recruitments.statuses.' . $this->value);
    }
}
