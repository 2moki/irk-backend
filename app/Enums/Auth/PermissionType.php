<?php

declare(strict_types=1);

namespace App\Enums\Auth;

enum PermissionType: string
{
    case RECRUITMENT_APPLICATION_ACCESS_OWN = 'recruitment_application_access_own';
    case RECRUITMENT_APPLICATION_ACCESS_ANY = 'recruitment_application_access_any';
    case RECRUITMENT_APPLICATION_MANAGE_OWN = 'recruitment_application_manage_own';
    case RECRUITMENT_APPLICATION_MANAGE_ANY = 'recruitment_application_manage_any';

    case RECRUITMENT_ACCESS = 'recruitment_access';
    case RECRUITMENT_MANAGE = 'recruitment_manage';

    case MAJOR_ACCESS = 'major_access';
    case MAJOR_MANAGE = 'major_manage';
}
