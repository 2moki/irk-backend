<?php

declare(strict_types=1);

namespace App\Enums\Auth;

enum RoleType: string
{
    case CANDIDATE = 'candidate';
    case EMPLOYEE = 'employee';
    case ADMIN = 'admin';
}
