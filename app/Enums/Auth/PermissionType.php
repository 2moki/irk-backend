<?php

declare(strict_types=1);

namespace App\Enums\Auth;

enum PermissionType: string
{
    case RECRUITMENT_APPLICATION_ACCESS = 'recruitment_application_access';
    case RECRUITMENT_APPLICATION_MANAGE = 'recruitment_application_manage';

    case MAJOR_ACCESS = 'major_access';
}
