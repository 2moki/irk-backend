<?php

declare(strict_types=1);

namespace App\Enums;

enum DocumentStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return __('document_statuses.' . $this->value);
    }
}
