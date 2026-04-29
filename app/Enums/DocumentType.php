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
}
