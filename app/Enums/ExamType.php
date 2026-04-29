<?php

declare(strict_types=1);

namespace App\Enums;

enum ExamType: string
{
    case NEW_MATURA = 'new_matura';
    case OLD_MATURA_PRE_1991 = 'old_matura_pre_1991';
    case OLD_MATURA_POST_1991 = 'old_matura_post_1991';
    case INTERNATIONAL_BACCALAUREATE = 'international_baccalaureate';
    case EUROPEAN_BACCALAUREATE = 'european_baccalaureate';
    case FOREIGN = 'foreign';

    public function label(): string
    {
        return __('exam_types.' . $this->value);
    }
}
