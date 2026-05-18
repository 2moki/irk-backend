<?php

declare(strict_types=1);

namespace App\Enums;

enum QualificationCategoryEnum: string
{
    case SCHOOL_GRADE = 'school_grade';
    case MATURA_GRADE = 'matura_grade';
    case MATURA_EXT_GRADE = 'matura_ext_grade';
    case CAREER_EXAM_GRADE = 'career_exam_grade';
    case QUALIFICATION_EXAM_GRADE = 'qualification_exam_grade';

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

    /**Id of qualification type*/
    public function id(): int
    {
        return match($this) {
            self::SCHOOL_GRADE => 1,
            self::MATURA_GRADE => 2,
            self::MATURA_EXT_GRADE => 3,
            self::CAREER_EXAM_GRADE => 4,
            self::QUALIFICATION_EXAM_GRADE => 5,
        };
    }
}
