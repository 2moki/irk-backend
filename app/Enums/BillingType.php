<?php

declare(strict_types=1);

namespace App\Enums;

enum BillingType: string
{
    case HIGHEST_FEE_ONLY = 'highest_fee_only';
    case EACH_MAJOR_SEPARATELY = 'each_major_separately';
    case EACH_GROUP_SEPARATELY = 'each_group_separately';

    public function label(): string
    {
        return __('billing_types.' . $this->value);
    }
}
