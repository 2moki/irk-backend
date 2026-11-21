<?php

declare(strict_types=1);

namespace App\Enums;

enum DocumentType: string
{
    case MATURA = 'matura';
    case MATURA_ANNEX = 'matura_annex';
    case PHOTO = 'photo';
    case IDENTITY_CARD = 'identity_card';
    case OLYMPIAD_CERTIFICATE = 'olympiad_certificate';
    case PASSPORT = 'passport';
    case CERTIFICATE = 'certificate';
    case DIPLOMA = 'diploma';
    case DISABILITY_CERTIFICATE = 'disability_certificate';
    case OTHER = 'other';

    public function label(): string
    {
        return __('document_types.' . $this->value);
    }

    /**Raw string value, stored in database*/
    public function rawString(): string
    {
        return $this->value;
    }

    public function id(): int
    {
        return match ($this) {
            self::MATURA => 1,
            self::MATURA_ANNEX => 2,
            self::PHOTO => 3,
            self::IDENTITY_CARD => 4,
            self::OLYMPIAD_CERTIFICATE => 5,
            self::PASSPORT => 6,
            self::CERTIFICATE => 7,
            self::DIPLOMA => 8,
            self::DISABILITY_CERTIFICATE => 9,
            self::OTHER => 10
        };
    }
}
