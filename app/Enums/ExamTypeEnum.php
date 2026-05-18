<?php

declare(strict_types=1);

namespace App\Enums;

enum ExamTypeEnum: string
{
    case NEW_MATURA = 'new_matura';
    case OLD_MATURA_PRE_1991 = 'old_matura_pre_1991';
    case OLD_MATURA_POST_1991 = 'old_matura_post_1991';
    case INTERNATIONAL_BACCALAUREATE = 'international_baccalaureate';
    case EUROPEAN_BACCALAUREATE = 'european_baccalaureate';
    case FOREIGN = 'foreign';

    /**Translated value, for UI*/
    public function label(): string
    {
        return __('exam_types.' . $this->value);
    }

    /**Raw string value, stored in database*/
    public function rawString(): string
    {
        return $this->value;
    }

    /**Id of exam type*/
    public function id(): int
    {
        return match($this) {
            self::NEW_MATURA => 1,
            self::OLD_MATURA_PRE_1991 => 2,
            self::OLD_MATURA_POST_1991 => 3,
            self::INTERNATIONAL_BACCALAUREATE => 4,
            self::EUROPEAN_BACCALAUREATE => 5,
            self::FOREIGN => 6
        };
    }
}
